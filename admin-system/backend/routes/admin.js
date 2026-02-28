/**
 * Admin System - Admin Routes
 * Handles authentication, CRUD operations, and admin management
 * 
 * @requires express
 * @requires mysql2 connection pool
 * @requires ../middleware/authorize
 * @requires ../lib/permissions
 */

import express from 'express';

/**
 * Create admin router with database pool
 * @param {Object} pool - MySQL2 connection pool
 * @param {Object} authMiddleware - Authorization middleware { authorize, authorizeAny }
 * @param {Object} permissionsLib - Permissions library { getPermissionsForAdmin }
 * @returns {express.Router} Express router
 */
export const createAdminRouter = (pool, authMiddleware, permissionsLib) => {
  const router = express.Router();
  const { authorize, authorizeAny } = authMiddleware;
  const { getPermissionsForAdmin } = permissionsLib;

  // ============================================
  // Password Utilities
  // ============================================

  /**
   * Hash password using custom algorithm
   * Note: For production, consider using bcrypt or argon2
   */
  const hashPassword = (password) => {
    let hash = 0;
    for (let i = 0; i < password.length; i++) {
      const char = password.charCodeAt(i);
      hash = ((hash << 5) - hash) + char;
      hash = hash & hash;
    }
    return 'hash_' + Math.abs(hash).toString(16);
  };

  const verifyPassword = (password, hash) => {
    return hashPassword(password) === hash;
  };

  // ============================================
  // Security Settings
  // ============================================

  const DEFAULT_SECURITY_SETTINGS = {
    maintenance_mode: 'false',
    max_login_attempts: '5',
    lock_duration_minutes: '15',
    password_min_length: '8',
    password_require_upper: 'true',
    password_require_lower: 'true',
    password_require_number: 'true',
    password_require_symbol: 'true',
    login_rate_limit_per_minute: '20',
    login_rate_limit_block_minutes: '2'
  };

  const fetchSecuritySettings = async () => {
    const keys = Object.keys(DEFAULT_SECURITY_SETTINGS);
    const placeholders = keys.map(() => '?').join(', ');
    const [rows] = await pool.query(
      `SELECT setting_key, setting_value FROM settings WHERE setting_key IN (${placeholders})`,
      keys
    );
    const settings = { ...DEFAULT_SECURITY_SETTINGS };
    rows.forEach(row => {
      settings[row.setting_key] = row.setting_value;
    });
    return settings;
  };

  const toIntSetting = (value, fallback) => {
    const parsed = parseInt(value, 10);
    return Number.isFinite(parsed) && parsed > 0 ? parsed : fallback;
  };

  const isTruthySetting = (value) => {
    const normalized = String(value || '').toLowerCase();
    return normalized === 'true' || normalized === '1' || normalized === 'yes' || normalized === 'on';
  };

  const validatePasswordPolicy = (password, settings) => {
    const minLength = toIntSetting(settings.password_min_length, 8);
    if (password.length < minLength) return `Password minimal ${minLength} karakter`;
    if (isTruthySetting(settings.password_require_upper) && !/[A-Z]/.test(password)) 
      return 'Password harus mengandung huruf besar';
    if (isTruthySetting(settings.password_require_lower) && !/[a-z]/.test(password)) 
      return 'Password harus mengandung huruf kecil';
    if (isTruthySetting(settings.password_require_number) && !/[0-9]/.test(password)) 
      return 'Password harus mengandung angka';
    if (isTruthySetting(settings.password_require_symbol) && !/[^\w\s]/.test(password)) 
      return 'Password harus mengandung simbol';
    return null;
  };

  // ============================================
  // Rate Limiting
  // ============================================

  const rateLimitStore = new Map();

  const checkRateLimit = (ip, limit, blockMinutes) => {
    const now = Date.now();
    const windowMs = 60 * 1000;
    const blockMs = blockMinutes * 60 * 1000;
    const entry = rateLimitStore.get(ip) || { count: 0, windowStart: now, blockedUntil: 0 };

    if (entry.blockedUntil && now < entry.blockedUntil) {
      return { blocked: true, retryAfterMs: entry.blockedUntil - now };
    }

    if (now - entry.windowStart > windowMs) {
      entry.windowStart = now;
      entry.count = 0;
    }

    entry.count += 1;
    if (entry.count > limit) {
      entry.blockedUntil = now + blockMs;
      rateLimitStore.set(ip, entry);
      return { blocked: true, retryAfterMs: blockMs };
    }

    rateLimitStore.set(ip, entry);
    return { blocked: false };
  };

  // ============================================
  // Activity Logging
  // ============================================

  const logActivity = async (adminId, adminName, action, description, ip) => {
    try {
      await pool.query(
        'INSERT INTO activity_logs (admin_id, admin_name, action, description, ip_address) VALUES (?, ?, ?, ?, ?)',
        [adminId, adminName, action, description, ip]
      );
    } catch (err) {
      console.error('Failed to log activity:', err);
    }
  };

  // ============================================
  // Status Converters
  // ============================================

  const requestStatusToUi = (status) => {
    const normalized = String(status || '').toLowerCase();
    if (normalized === 'pending') return 'menunggu';
    if (normalized === 'approved') return 'diterima';
    if (normalized === 'rejected') return 'ditolak';
    return status;
  };

  const requestStatusToDb = (status) => {
    const normalized = String(status || '').toLowerCase();
    if (normalized === 'menunggu' || normalized === 'pending') return 'pending';
    if (normalized === 'diterima' || normalized === 'approved') return 'approved';
    if (normalized === 'ditolak' || normalized === 'rejected') return 'rejected';
    return 'pending';
  };

  const laporanStatusToDb = (status) => {
    const normalized = String(status || '').toLowerCase();
    if (normalized === 'pending' || normalized === 'menunggu') return 'pending';
    if (normalized === 'processed' || normalized === 'diproses') return 'processed';
    if (normalized === 'completed' || normalized === 'selesai') return 'completed';
    return 'pending';
  };

  // ============================================
  // ROUTES
  // ============================================

  /**
   * POST /api/admin/login
   * Authenticate admin user
   */
  router.post('/login', async (req, res) => {
    try {
      const { username, password } = req.body;
      const ip = req.ip || req.connection.remoteAddress;
      
      if (!username || !password) {
        return res.status(400).json({ error: 'Username dan password harus diisi' });
      }

      const settings = await fetchSecuritySettings();
      const maxLoginAttempts = toIntSetting(settings.max_login_attempts, 5);
      const lockDurationMinutes = toIntSetting(settings.lock_duration_minutes, 15);
      const rateLimitPerMinute = toIntSetting(settings.login_rate_limit_per_minute, 20);
      const rateLimitBlockMinutes = toIntSetting(settings.login_rate_limit_block_minutes, 2);

      // Check rate limit
      const rateLimit = checkRateLimit(ip, rateLimitPerMinute, rateLimitBlockMinutes);
      if (rateLimit.blocked) {
        const retryMinutes = Math.ceil(rateLimit.retryAfterMs / 60000);
        return res.status(429).json({ 
          error: `Terlalu banyak percobaan. Coba lagi dalam ${retryMinutes} menit.` 
        });
      }

      // Check failed login attempts
      const [failedRows] = await pool.query(
        "SELECT created_at FROM activity_logs WHERE admin_name = ? AND action = 'LOGIN_FAILED' AND created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE) ORDER BY created_at DESC",
        [username, lockDurationMinutes]
      );

      if (failedRows.length >= maxLoginAttempts) {
        const lastFailedAt = new Date(failedRows[0].created_at).getTime();
        const remainingMs = (lockDurationMinutes * 60 * 1000) - (Date.now() - lastFailedAt);
        if (remainingMs > 0) {
          const remainingMinutes = Math.ceil(remainingMs / 60000);
          await logActivity(null, username, 'LOGIN_LOCKED', 'Percobaan login diblokir sementara', ip);
          return res.status(429).json({ 
            error: `Akun terkunci sementara. Coba lagi dalam ${remainingMinutes} menit.` 
          });
        }
      }

      // Find admin
      const [rows] = await pool.query('SELECT * FROM admins WHERE username = ?', [username]);

      if (rows.length === 0) {
        await logActivity(null, username, 'LOGIN_FAILED', 'Username tidak ditemukan', ip);
        return res.status(401).json({ error: 'Akun tidak ditemukan' });
      }

      const admin = rows[0];

      // Check status
      if (admin.status !== 'aktif') {
        await logActivity(admin.id, admin.username, 'LOGIN_FAILED', 'Akun nonaktif', ip);
        return res.status(403).json({ error: 'Akun tidak aktif' });
      }

      // Check maintenance mode
      if (isTruthySetting(settings.maintenance_mode) && admin.role !== 'super_admin') {
        await logActivity(admin.id, admin.username, 'LOGIN_BLOCKED', 'Mode perawatan aktif', ip);
        return res.status(503).json({ 
          error: 'Sistem sedang dalam mode perawatan. Silakan coba lagi nanti.' 
        });
      }

      // Verify password
      const isValidPassword = verifyPassword(password, admin.password);

      if (!isValidPassword) {
        await logActivity(admin.id, admin.username, 'LOGIN_FAILED', 'Password salah', ip);
        return res.status(401).json({ error: 'Password salah' });
      }

      // Update last login
      await pool.query('UPDATE admins SET last_login = NOW() WHERE id = ?', [admin.id]);
      
      await logActivity(admin.id, admin.username, 'LOGIN_SUCCESS', 'Login berhasil', ip);

      // Get permissions
      const permissions = await getPermissionsForAdmin(pool, admin.id, admin.role);

      res.json({
        id: admin.id,
        username: admin.username,
        nama: admin.nama,
        role: admin.role,
        email: admin.email,
        no_hp: admin.no_hp,
        kecamatan: admin.kecamatan || '',
        desa: admin.desa || '',
        kode_kecamatan: admin.kode_kecamatan || '',
        kode_desa: admin.kode_desa || '',
        kode_unik_sppg: admin.kode_unik_sppg || '',
        permissions
      });
    } catch (error) {
      console.error('Login error:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/admin/me
   * Get current admin profile
   */
  router.get('/me', async (req, res) => {
    try {
      const adminId = parseInt(req.headers['x-admin-id'], 10);
      if (!adminId) {
        return res.status(400).json({ error: 'Admin ID tidak valid' });
      }

      const [rows] = await pool.query(
        'SELECT id, username, nama, role, email, no_hp, kecamatan, kode_kecamatan, desa, kode_desa, kode_unik_sppg, status, last_login FROM admins WHERE id = ? LIMIT 1',
        [adminId]
      );

      if (!rows.length) {
        return res.status(404).json({ error: 'Admin tidak ditemukan' });
      }

      return res.json(rows[0]);
    } catch (error) {
      console.error('Error fetching current admin profile:', error);
      return res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/admin/list
   * Get all admins (requires admin.manage or stats.view permission)
   */
  router.get('/list', authorizeAny(['admin.manage', 'stats.view']), async (req, res) => {
    try {
      const [rows] = await pool.query(
        'SELECT id, username, nama, role, email, no_hp, kecamatan, kode_kecamatan, desa, kode_desa, kode_unik_sppg, status, last_login, created_at FROM admins ORDER BY created_at DESC'
      );
      res.json(rows);
    } catch (error) {
      console.error('Error fetching admins:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * POST /api/admin/register
   * Create new admin (requires admin.manage or request.update permission)
   */
  router.post('/register', authorizeAny(['admin.manage', 'request.update']), async (req, res) => {
    try {
      const { username, password, nama, role, email, no_hp, kecamatan, kode_kecamatan, desa, kode_desa, kode_unik_sppg } = req.body;
      const actorRole = String(req.admin?.role || '').toLowerCase();
      
      if (!username || !password || !nama || !role) {
        return res.status(400).json({ error: 'Data tidak lengkap' });
      }

      // Badan Gizi can only create SPPG admins
      if (actorRole === 'badan_gizi' && role !== 'sppg_admin') {
        return res.status(403).json({ error: 'Badan Gizi hanya dapat membuat akun Admin SPPG' });
      }

      // Validate password policy
      const settings = await fetchSecuritySettings();
      const policyError = validatePasswordPolicy(password, settings);
      if (policyError) {
        return res.status(400).json({ error: policyError });
      }

      const hashedPassword = hashPassword(password);

      const [result] = await pool.query(
        'INSERT INTO admins (username, password, nama, role, email, no_hp, kecamatan, kode_kecamatan, desa, kode_desa, kode_unik_sppg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [username, hashedPassword, nama, role, email || null, no_hp || null, kecamatan || null, kode_kecamatan || null, desa || null, kode_desa || null, kode_unik_sppg || null]
      );

      res.status(201).json({ id: result.insertId, username, nama, role, kecamatan, desa });
    } catch (error) {
      if (error.code === 'ER_DUP_ENTRY') {
        return res.status(400).json({ error: 'Username sudah digunakan' });
      }
      console.error('Register error:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/admin/:id
   * Update admin (requires admin.manage or request.update permission)
   */
  router.put('/:id', authorizeAny(['admin.manage', 'request.update']), async (req, res) => {
    try {
      const { id } = req.params;
      const { nama, email, no_hp, status, kecamatan, kode_kecamatan, desa, kode_desa, kode_unik_sppg } = req.body;
      const actorRole = String(req.admin?.role || '').toLowerCase();

      // Badan Gizi can only update SPPG admins
      if (actorRole === 'badan_gizi') {
        const [targetRows] = await pool.query('SELECT role FROM admins WHERE id = ? LIMIT 1', [id]);
        if (!targetRows.length) return res.status(404).json({ error: 'Admin tidak ditemukan' });
        if (targetRows[0].role !== 'sppg_admin') {
          return res.status(403).json({ error: 'Badan Gizi hanya dapat mengubah akun Admin SPPG' });
        }
      }
      
      await pool.query(
        'UPDATE admins SET nama = ?, email = ?, no_hp = ?, status = ?, kecamatan = ?, kode_kecamatan = ?, desa = ?, kode_desa = ?, kode_unik_sppg = ? WHERE id = ?',
        [nama, email, no_hp, status, kecamatan || null, kode_kecamatan || null, desa || null, kode_desa || null, kode_unik_sppg || null, id]
      );

      res.json({ message: 'Admin updated' });
    } catch (error) {
      console.error('Update error:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * DELETE /api/admin/:id
   * Delete admin and related data (requires admin.manage or request.update permission)
   */
  router.delete('/:id', authorizeAny(['admin.manage', 'request.update']), async (req, res) => {
    let connection;
    try {
      const { id } = req.params;
      const adminId = Number(id);
      if (!Number.isFinite(adminId) || adminId <= 0) {
        return res.status(400).json({ error: 'ID admin tidak valid' });
      }

      connection = await pool.getConnection();
      await connection.beginTransaction();

      const [rows] = await connection.query(
        'SELECT id, role, nama, username FROM admins WHERE id = ? LIMIT 1',
        [adminId]
      );
      
      if (rows.length === 0) {
        await connection.rollback();
        return res.status(404).json({ error: 'Admin tidak ditemukan' });
      }

      const actorRole = String(req.admin?.role || '').toLowerCase();
      
      // Cannot delete super admin
      if (rows[0].role === 'super_admin') {
        await connection.rollback();
        return res.status(400).json({ error: 'Tidak bisa menghapus Super Admin' });
      }
      
      // Badan Gizi can only delete SPPG admins
      if (actorRole === 'badan_gizi' && rows[0].role !== 'sppg_admin') {
        await connection.rollback();
        return res.status(403).json({ error: 'Badan Gizi hanya dapat menghapus akun Admin SPPG' });
      }

      // Delete related data
      await connection.query('DELETE FROM activity_logs WHERE admin_id = ?', [adminId]);
      await connection.query('DELETE FROM admin_permissions WHERE admin_id = ?', [adminId]);
      await connection.query('DELETE FROM admins WHERE id = ?', [adminId]);

      await connection.commit();
      connection.release();
      connection = null;

      res.json({
        message: 'Admin berhasil dihapus',
        deleted_admin_id: adminId
      });
    } catch (error) {
      if (connection) {
        try { await connection.rollback(); } catch {}
        connection.release();
      }
      console.error('Delete error:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/admin/:id/password
   * Change admin password (requires admin.manage or request.update permission)
   */
  router.put('/:id/password', authorizeAny(['admin.manage', 'request.update']), async (req, res) => {
    try {
      const { id } = req.params;
      const { password } = req.body;
      const actorRole = String(req.admin?.role || '').toLowerCase();
      
      if (!password) {
        return res.status(400).json({ error: 'Password harus diisi' });
      }

      // Badan Gizi can only reset SPPG admin passwords
      if (actorRole === 'badan_gizi') {
        const [targetRows] = await pool.query('SELECT role FROM admins WHERE id = ? LIMIT 1', [id]);
        if (!targetRows.length) return res.status(404).json({ error: 'Admin tidak ditemukan' });
        if (targetRows[0].role !== 'sppg_admin') {
          return res.status(403).json({ error: 'Badan Gizi hanya dapat reset password Admin SPPG' });
        }
      }

      // Validate password policy
      const settings = await fetchSecuritySettings();
      const policyError = validatePasswordPolicy(password, settings);
      if (policyError) {
        return res.status(400).json({ error: policyError });
      }

      const hashedPassword = hashPassword(password);
      await pool.query('UPDATE admins SET password = ? WHERE id = ?', [hashedPassword, id]);

      res.json({ message: 'Password updated' });
    } catch (error) {
      console.error('Password update error:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/admin/logs
   * Get activity logs (requires logs.view permission)
   */
  router.get('/logs', authorize('logs.view'), async (req, res) => {
    try {
      const limit = parseInt(req.query.limit) || 100;
      const params = [];
      let query = `
        SELECT
          l.id,
          l.admin_id,
          COALESCE(a.username, l.admin_name) AS username,
          COALESCE(a.nama, l.admin_name) AS admin_name,
          COALESCE(a.role, '') AS role,
          l.action,
          l.description AS details,
          l.ip_address,
          l.created_at
        FROM activity_logs l
        LEFT JOIN admins a ON a.id = l.admin_id
      `;

      // Badan Gizi can only see SPPG admin logs
      if (req.admin?.role === 'badan_gizi') {
        query += `
          WHERE (
            a.role = 'sppg_admin'
            OR (
              a.role IS NULL
              AND EXISTS (
                SELECT 1
                FROM admins sa
                WHERE sa.username = l.admin_name
                AND sa.role = 'sppg_admin'
              )
            )
          )
        `;
      }

      query += ' ORDER BY l.created_at DESC LIMIT ?';
      params.push(limit);

      const [rows] = await pool.query(query, params);
      res.json(rows);
    } catch (error) {
      console.error('Error fetching logs:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/admin/laporan
   * Get reports (requires laporan.view permission)
   */
  router.get('/laporan', authorize('laporan.view'), async (req, res) => {
    try {
      const [rows] = await pool.query(
        `SELECT l.*, a.nama as admin_nama
         FROM laporan l 
         LEFT JOIN admins a ON l.admin_id = a.id 
         ORDER BY l.created_at DESC`
      );
      res.json(rows);
    } catch (error) {
      console.error('Error fetching laporan:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * POST /api/admin/laporan
   * Create report (requires laporan.create permission)
   */
  router.post('/laporan', authorize('laporan.create'), async (req, res) => {
    try {
      const { admin_id, judul, deskripsi } = req.body;
      const [result] = await pool.query(
        'INSERT INTO laporan (admin_id, judul, deskripsi) VALUES (?, ?, ?)',
        [admin_id, judul, deskripsi]
      );
      await logActivity(
        req.admin?.id || admin_id || null,
        req.admin?.nama || req.admin?.username || String(admin_id || 'Unknown'),
        'LAPORAN_CREATE',
        `Membuat laporan: ${judul || '-'}`,
        req.ip
      );
      res.status(201).json({ id: result.insertId });
    } catch (error) {
      console.error('Error creating laporan:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/admin/laporan/:id/status
   * Update report status (requires laporan.update permission)
   */
  router.put('/laporan/:id/status', authorize('laporan.update'), async (req, res) => {
    try {
      const { id } = req.params;
      const { status } = req.body;
      await pool.query('UPDATE laporan SET status = ? WHERE id = ?', [laporanStatusToDb(status), id]);
      res.json({ message: 'Status updated' });
    } catch (error) {
      console.error('Error updating laporan:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/admin/request-data
   * Get data requests (requires request.view permission)
   */
  router.get('/request-data', authorize('request.view'), async (req, res) => {
    try {
      const [rows] = await pool.query(
        `SELECT r.*, a.nama as admin_nama
         FROM request_data r 
         LEFT JOIN admins a ON r.admin_id = a.id 
         ORDER BY r.created_at DESC`
      );
      const normalizedRows = rows.map((row) => ({ ...row, status: requestStatusToUi(row.status) }));
      res.json(normalizedRows);
    } catch (error) {
      console.error('Error fetching requests:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * POST /api/admin/request-data
   * Create data request (requires request.create permission)
   */
  router.post('/request-data', authorize('request.create'), async (req, res) => {
    try {
      const { admin_id, type, data_lama, data_baru } = req.body;
      const [result] = await pool.query(
        'INSERT INTO request_data (admin_id, type, data_lama, data_baru) VALUES (?, ?, ?, ?)',
        [admin_id, type, JSON.stringify(data_lama), JSON.stringify(data_baru)]
      );
      await logActivity(
        req.admin?.id || admin_id || null,
        req.admin?.nama || req.admin?.username || String(admin_id || 'Unknown'),
        'REQUEST_CREATE',
        `Membuat request data tipe: ${type || '-'}`,
        req.ip
      );
      res.status(201).json({ id: result.insertId });
    } catch (error) {
      console.error('Error creating request:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/admin/request-data/:id/status
   * Update request status (requires request.update permission)
   */
  router.put('/request-data/:id/status', authorize('request.update'), async (req, res) => {
    try {
      const { id } = req.params;
      const { status } = req.body;
      const nextStatus = requestStatusToDb(status);

      await pool.query('UPDATE request_data SET status = ? WHERE id = ?', [nextStatus, id]);
      res.json({ message: 'Status updated' });
    } catch (error) {
      console.error('Error updating request:', error);
      res.status(500).json({ error: error.message });
    }
  });

  return router;
};

// Export for backward compatibility
export default (pool, authMiddleware, permissionsLib) => {
  return createAdminRouter(pool, authMiddleware, permissionsLib);
};
