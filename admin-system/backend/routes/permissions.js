/**
 * Admin System - Permissions Routes
 * Handles permission and role management
 * 
 * @requires express
 * @requires mysql2 connection pool
 * @requires ../middleware/authorize
 * @requires ../lib/permissions
 */

import express from 'express';

/**
 * Create permissions router with database pool
 * @param {Object} pool - MySQL2 connection pool
 * @param {Object} authMiddleware - Authorization middleware { authorize }
 * @param {Object} permissionsLib - Permissions library
 * @returns {express.Router} Express router
 */
export const createPermissionsRouter = (pool, authMiddleware, permissionsLib) => {
  const router = express.Router();
  const { authorize } = authMiddleware;
  const { getAllPermissions, getRolesWithPermissions, updateRolePermissions } = permissionsLib;

  /**
   * GET /api/permissions/catalog
   * Get all available permissions
   * Requires: permissions.manage
   */
  router.get('/catalog', authorize('permissions.manage'), async (req, res) => {
    try {
      const permissions = await getAllPermissions(pool);
      res.json(permissions);
    } catch (error) {
      console.error('Error fetching permissions:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * GET /api/permissions/roles
   * Get all roles with their assigned permissions
   * Requires: permissions.manage
   */
  router.get('/roles', authorize('permissions.manage'), async (req, res) => {
    try {
      const roles = await getRolesWithPermissions(pool);
      res.json(roles);
    } catch (error) {
      console.error('Error fetching role permissions:', error);
      res.status(500).json({ error: error.message });
    }
  });

  /**
   * PUT /api/permissions/roles/:code
   * Update permissions for a specific role
   * Requires: permissions.manage
   * 
   * Body: { permissions: ['permission.code1', 'permission.code2', ...] }
   */
  router.put('/roles/:code', authorize('permissions.manage'), async (req, res) => {
    try {
      const { code } = req.params;
      const { permissions } = req.body;
      
      await updateRolePermissions(pool, code, permissions || []);
      res.json({ message: 'Role permissions updated' });
    } catch (error) {
      console.error('Error updating role permissions:', error);
      res.status(500).json({ error: error.message });
    }
  });

  return router;
};

// Export for backward compatibility
export default (pool, authMiddleware, permissionsLib) => {
  return createPermissionsRouter(pool, authMiddleware, permissionsLib);
};
