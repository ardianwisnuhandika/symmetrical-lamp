/**
 * Admin System - Security Bootstrap
 * Initializes roles, permissions, and role-permission mappings
 * 
 * @requires mysql2 connection pool
 */

const PERMISSIONS = [
  { code: 'admin.manage', label: 'Manajemen Admin', description: 'Kelola akun admin dan peran' },
  { code: 'permissions.manage', label: 'Manajemen Akses', description: 'Kelola role dan permission' },
  { code: 'settings.manage', label: 'Pengaturan Global', description: 'Ubah konfigurasi sistem' },
  { code: 'logs.view', label: 'Lihat Log', description: 'Akses log aktivitas' },
  { code: 'laporan.view', label: 'Lihat Laporan', description: 'Akses laporan masalah' },
  { code: 'laporan.create', label: 'Buat Laporan', description: 'Buat laporan masalah' },
  { code: 'laporan.update', label: 'Update Laporan', description: 'Ubah status laporan' },
  { code: 'request.view', label: 'Lihat Request', description: 'Akses request data' },
  { code: 'request.create', label: 'Buat Request', description: 'Buat request data' },
  { code: 'request.update', label: 'Update Request', description: 'Ubah status request' },
  { code: 'sppg.view', label: 'Lihat SPPG', description: 'Lihat data SPPG' },
  { code: 'sppg.manage', label: 'Kelola SPPG', description: 'Tambah/ubah/hapus data SPPG' },
  { code: 'export.data', label: 'Export Data', description: 'Ekspor data SPPG/admin' },
  { code: 'stats.view', label: 'Lihat Statistik', description: 'Akses statistik sistem' },
  { code: 'server.view', label: 'Monitoring Server', description: 'Lihat status server' }
];

const ROLES = [
  { code: 'super_admin', name: 'Super Admin' },
  { code: 'badan_gizi', name: 'Badan Gizi' },
  { code: 'sppg_admin', name: 'Admin SPPG' }
];

const ROLE_PERMISSIONS = {
  super_admin: PERMISSIONS.map(p => p.code),
  badan_gizi: [
    'laporan.view',
    'laporan.create',
    'laporan.update',
    'request.view',
    'request.create',
    'request.update',
    'sppg.view',
    'export.data',
    'stats.view',
    'logs.view'
  ],
  sppg_admin: [
    'sppg.view',
    'sppg.manage'
  ]
};

/**
 * Bootstrap security tables and default data
 * @param {Object} pool - MySQL2 connection pool
 */
export const bootstrapSecurity = async (pool) => {
  // Create roles table
  await pool.query(
    `CREATE TABLE IF NOT EXISTS roles (
      id INT AUTO_INCREMENT PRIMARY KEY,
      code VARCHAR(50) NOT NULL UNIQUE,
      name VARCHAR(100) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )`
  );

  // Create permissions table
  await pool.query(
    `CREATE TABLE IF NOT EXISTS permissions (
      id INT AUTO_INCREMENT PRIMARY KEY,
      code VARCHAR(100) NOT NULL UNIQUE,
      label VARCHAR(150) NOT NULL,
      description TEXT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )`
  );

  // Create role_permissions junction table
  await pool.query(
    `CREATE TABLE IF NOT EXISTS role_permissions (
      role_id INT NOT NULL,
      permission_id INT NOT NULL,
      PRIMARY KEY (role_id, permission_id),
      FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
      FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
    )`
  );

  // Create admin_permissions override table
  await pool.query(
    `CREATE TABLE IF NOT EXISTS admin_permissions (
      admin_id INT NOT NULL,
      permission_id INT NOT NULL,
      allowed TINYINT(1) DEFAULT 1,
      PRIMARY KEY (admin_id, permission_id),
      FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
      FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
    )`
  );

  // Insert roles
  for (const role of ROLES) {
    await pool.query('INSERT IGNORE INTO roles (code, name) VALUES (?, ?)', [role.code, role.name]);
  }

  // Insert permissions
  for (const perm of PERMISSIONS) {
    await pool.query(
      'INSERT IGNORE INTO permissions (code, label, description) VALUES (?, ?, ?)',
      [perm.code, perm.label, perm.description]
    );
  }

  // Get role and permission IDs
  const [roleRows] = await pool.query('SELECT id, code FROM roles');
  const [permRows] = await pool.query('SELECT id, code FROM permissions');
  const roleIdByCode = Object.fromEntries(roleRows.map(row => [row.code, row.id]));
  const permIdByCode = Object.fromEntries(permRows.map(row => [row.code, row.id]));

  // Assign permissions to roles
  for (const [roleCode, permCodes] of Object.entries(ROLE_PERMISSIONS)) {
    const roleId = roleIdByCode[roleCode];
    if (!roleId) continue;
    
    // Check if already initialized
    const [[{ count }]] = await pool.query(
      'SELECT COUNT(*) as count FROM role_permissions WHERE role_id = ?',
      [roleId]
    );
    if (count > 0) continue;
    
    // Insert role permissions
    const values = permCodes
      .map(code => permIdByCode[code])
      .filter(Boolean)
      .map(permissionId => [roleId, permissionId]);
    
    if (values.length > 0) {
      await pool.query('INSERT INTO role_permissions (role_id, permission_id) VALUES ?', [values]);
    }
  }
};

export { PERMISSIONS, ROLES, ROLE_PERMISSIONS };
