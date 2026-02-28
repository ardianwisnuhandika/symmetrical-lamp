/**
 * Admin System - Settings Routes
 * Handles system configuration and statistics
 * 
 * @requires express
 * @requires mysql2 connection pool
 * @requires ../middleware/authorize
 */

import express from 'express';

/**
 * Create settings router with database pool
 * @param {Object} pool - MySQL2 connection pool
 * @param {Object} authMiddleware - Authorization middleware { authorize, authorizeAny }
 * @returns {express.Router} Express router
 */
export const createSettingsRouter = (pool, authMiddleware) => {
  const router = express.Router();
  const { authorize, authorizeAny } = authMiddleware;

  /**
   * GET /api/settings
   * Get all system settings
   * Public endpoint (no auth required)
   */
  router.get('/', async (req, res) => {
    try {
      const [rows] = await pool.query('SELECT * FROM settings');
      const settings = {};
      rows.forEach(row => {
        settings[row.setting_key] = row.setting_value;
      });
      res.json(settings);
    } catch (error) {
      console.error('Error fetching settings:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/settings
   * Update system settings
   * Requires: settings.manage
   * 
   * Body: { settings: { key1: 'value1', key2: 'value2', ... } }
   */
  router.put('/', authorize('settings.manage'), async (req, res) => {
    try {
      const { settings } = req.body;

      for (const [key, value] of Object.entries(settings)) {
        await pool.query(
          'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?',
          [key, value, value]
        );
      }

      res.json({ message: 'Settings updated' });
    } catch (error) {
      console.error('Error updating settings:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/settings/announcement
   * Update announcement message
   * Requires: settings.manage OR request.update
   * 
   * Body: { scope: 'global|sppg', message: 'announcement text' }
   */
  router.put('/announcement', authorizeAny(['settings.manage', 'request.update']), async (req, res) => {
    try {
      const role = String(req.admin?.role || '').toLowerCase();
      const requestedScope = String(req.body?.scope || '').toLowerCase();
      const message = String(req.body?.message || '').trim();
      
      // Determine setting key based on role and scope
      const settingKey = role === 'badan_gizi'
        ? 'sppg_announcement'
        : (requestedScope === 'sppg' ? 'sppg_announcement' : 'global_announcement');

      await pool.query(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?',
        [settingKey, message, message]
      );
      
      res.json({ message: 'Announcement updated', key: settingKey });
    } catch (error) {
      console.error('Error updating announcement:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/settings/stats
   * Get system statistics
   * Public endpoint (no auth required)
   */
  router.get('/stats', async (req, res) => {
    try {
      // Total admins
      const [[{ total_admin }]] = await pool.query('SELECT COUNT(*) as total_admin FROM admins');
      
      // Visitor count
      const [visitorRows] = await pool.query("SELECT setting_value as visitor FROM settings WHERE setting_key = 'visitor_count'");
      const visitor = visitorRows[0]?.visitor || 0;

      // Login today
      const [[{ count: login_today }]] = await pool.query(
        "SELECT COUNT(*) as count FROM activity_logs WHERE action = 'LOGIN_SUCCESS' AND DATE(created_at) = CURDATE()"
      );

      // Login this week
      const [[{ count: login_week }]] = await pool.query(
        "SELECT COUNT(*) as count FROM activity_logs WHERE action = 'LOGIN_SUCCESS' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
      );

      // Recent activity
      const [recent_logs] = await pool.query(
        "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10"
      );

      res.json({
        total_admin: total_admin || 0,
        visitor_count: parseInt(visitor || 0),
        login_today: login_today || 0,
        login_week: login_week || 0,
        recent_activity: recent_logs
      });
    } catch (error) {
      console.error('Error fetching stats:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/settings/server-health
   * Get server health metrics
   * Public endpoint (no auth required)
   */
  router.get('/server-health', async (req, res) => {
    try {
      // Database size in MB
      const [[dbSizeRow]] = await pool.query(
        `SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
         FROM information_schema.tables
         WHERE table_schema = DATABASE()`
      );

      // Active connections
      const [[connRow]] = await pool.query(
        `SELECT COUNT(*) AS active_connections FROM information_schema.processlist WHERE db = DATABASE()`
      );

      // Server uptime (seconds)
      const [[uptimeRow]] = await pool.query(
        `SHOW GLOBAL STATUS LIKE 'Uptime'`
      );

      // Total queries executed
      const [[queriesRow]] = await pool.query(
        `SHOW GLOBAL STATUS LIKE 'Queries'`
      );

      // Total tables
      const [[tableCountRow]] = await pool.query(
        `SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema = DATABASE()`
      );

      // Max connections allowed
      const [[maxConnRow]] = await pool.query(
        `SHOW VARIABLES LIKE 'max_connections'`
      );

      // Total connections ever
      const [[totalConnRow]] = await pool.query(
        `SHOW GLOBAL STATUS LIKE 'Threads_connected'`
      );

      const uptimeSeconds = parseInt(uptimeRow?.Value || 0);
      const totalQueries = parseInt(queriesRow?.Value || 0);
      const maxConnections = parseInt(maxConnRow?.Value || 100);
      const threadsConnected = parseInt(totalConnRow?.Value || 0);
      const qps = uptimeSeconds > 0 ? (totalQueries / uptimeSeconds).toFixed(1) : 0;

      res.json({
        db_size_mb: parseFloat(dbSizeRow?.size_mb || 0),
        active_connections: connRow?.active_connections || 0,
        threads_connected: threadsConnected,
        max_connections: maxConnections,
        connection_usage_pct: Math.round((threadsConnected / maxConnections) * 100),
        uptime_seconds: uptimeSeconds,
        total_queries: totalQueries,
        queries_per_second: parseFloat(qps),
        table_count: tableCountRow?.table_count || 0,
        status: 'online',
        timestamp: new Date().toISOString()
      });
    } catch (error) {
      console.error('Error fetching server health:', error);
      res.json({
        status: 'error',
        error: error.message,
        timestamp: new Date().toISOString()
      });
    }
  });

  return router;
};

// Export for backward compatibility
export default (pool, authMiddleware) => {
  return createSettingsRouter(pool, authMiddleware);
};
