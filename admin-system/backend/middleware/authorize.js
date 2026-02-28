/**
 * Admin System - Authorization Middleware
 * Protects routes with permission-based access control
 * 
 * @requires mysql2 connection pool
 * @requires ./lib/permissions
 */

import { getPermissionsForAdmin } from '../lib/permissions.js';

/**
 * Resolve admin from database
 * @param {Object} pool - MySQL2 connection pool
 * @param {number} adminId - Admin ID
 * @returns {Promise<Object|null>} Admin object or null
 */
const resolveAdmin = async (pool, adminId) => {
  if (!adminId) return null;
  const [rows] = await pool.query('SELECT id, username, nama, role FROM admins WHERE id = ?', [adminId]);
  return rows[0] || null;
};

/**
 * Create authorization middleware factory
 * @param {Object} pool - MySQL2 connection pool
 * @returns {Object} Middleware functions
 */
export const createAuthMiddleware = (pool) => {
  /**
   * Authorize middleware - requires specific permission
   * @param {string} permissionCode - Required permission code
   * @returns {Function} Express middleware
   */
  const authorize = (permissionCode) => async (req, res, next) => {
    try {
      // Extract admin ID from request
      const adminId = req.headers['x-admin-id'] || req.body?.admin_id || req.query?.admin_id;
      if (!adminId) {
        return res.status(401).json({ error: 'Admin ID diperlukan' });
      }

      // Resolve admin
      const admin = await resolveAdmin(pool, adminId);
      if (!admin) {
        return res.status(401).json({ error: 'Admin tidak ditemukan' });
      }

      // Get admin permissions
      const permissions = await getPermissionsForAdmin(pool, admin.id, admin.role);
      
      // Check permission
      if (!permissions.includes(permissionCode)) {
        return res.status(403).json({ error: 'Akses ditolak' });
      }

      // Attach admin and permissions to request
      req.admin = admin;
      req.permissions = permissions;
      return next();
    } catch (error) {
      console.error('Authorize error:', error);
      return res.status(500).json({ error: 'Gagal memvalidasi akses' });
    }
  };

  /**
   * AuthorizeAny middleware - requires at least one of the permissions
   * @param {Array<string>} permissionCodes - Array of permission codes
   * @returns {Function} Express middleware
   */
  const authorizeAny = (permissionCodes = []) => async (req, res, next) => {
    try {
      // Extract admin ID from request
      const adminId = req.headers['x-admin-id'] || req.body?.admin_id || req.query?.admin_id;
      if (!adminId) {
        return res.status(401).json({ error: 'Admin ID diperlukan' });
      }

      // Resolve admin
      const admin = await resolveAdmin(pool, adminId);
      if (!admin) {
        return res.status(401).json({ error: 'Admin tidak ditemukan' });
      }

      // Get admin permissions
      const permissions = await getPermissionsForAdmin(pool, admin.id, admin.role);
      
      // Check if admin has any of the required permissions
      const hasAny = permissionCodes.some(code => permissions.includes(code));
      if (!hasAny) {
        return res.status(403).json({ error: 'Akses ditolak' });
      }

      // Attach admin and permissions to request
      req.admin = admin;
      req.permissions = permissions;
      return next();
    } catch (error) {
      console.error('AuthorizeAny error:', error);
      return res.status(500).json({ error: 'Gagal memvalidasi akses' });
    }
  };

  return { authorize, authorizeAny };
};

/**
 * Legacy export for backward compatibility
 * Note: Requires pool to be imported from '../config/database.js'
 */
export const authorize = (permissionCode) => async (req, res, next) => {
  // This will be overridden when using createAuthMiddleware
  throw new Error('Please use createAuthMiddleware(pool) to create middleware functions');
};

export const authorizeAny = (permissionCodes = []) => async (req, res, next) => {
  // This will be overridden when using createAuthMiddleware
  throw new Error('Please use createAuthMiddleware(pool) to create middleware functions');
};
