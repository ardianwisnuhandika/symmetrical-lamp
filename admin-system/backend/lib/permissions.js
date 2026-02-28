/**
 * Admin System - Permission Management Library
 * Handles permission queries and role-permission mappings
 * 
 * @requires mysql2 connection pool
 */

/**
 * Fetch role ID by role code
 * @param {Object} pool - MySQL2 connection pool
 * @param {string} roleCode - Role code (e.g., 'super_admin')
 * @returns {Promise<number|null>} Role ID or null
 */
const fetchRoleId = async (pool, roleCode) => {
  const [rows] = await pool.query('SELECT id FROM roles WHERE code = ?', [roleCode]);
  return rows[0]?.id || null;
};

/**
 * Get all available permissions
 * @param {Object} pool - MySQL2 connection pool
 * @returns {Promise<Array>} Array of permission objects
 */
export const getAllPermissions = async (pool) => {
  const [rows] = await pool.query('SELECT id, code, label, description FROM permissions ORDER BY code ASC');
  return rows;
};

/**
 * Get permissions for a specific role
 * @param {Object} pool - MySQL2 connection pool
 * @param {string} roleCode - Role code
 * @returns {Promise<Array<string>>} Array of permission codes
 */
export const getPermissionsForRole = async (pool, roleCode) => {
  const roleId = await fetchRoleId(pool, roleCode);
  if (!roleId) return [];
  
  const [rows] = await pool.query(
    `SELECT p.code
     FROM role_permissions rp
     JOIN permissions p ON p.id = rp.permission_id
     WHERE rp.role_id = ?
     ORDER BY p.code ASC`,
    [roleId]
  );
  return rows.map(row => row.code);
};

/**
 * Get effective permissions for an admin (role + overrides)
 * @param {Object} pool - MySQL2 connection pool
 * @param {number} adminId - Admin ID
 * @param {string} roleCode - Admin's role code
 * @returns {Promise<Array<string>>} Array of permission codes
 */
export const getPermissionsForAdmin = async (pool, adminId, roleCode) => {
  // Get base role permissions
  const rolePermissions = new Set(await getPermissionsForRole(pool, roleCode));
  
  // Get admin-specific permission overrides
  const [overrideRows] = await pool.query(
    `SELECT p.code, ap.allowed
     FROM admin_permissions ap
     JOIN permissions p ON p.id = ap.permission_id
     WHERE ap.admin_id = ?`,
    [adminId]
  );
  
  // Apply overrides
  overrideRows.forEach(row => {
    if (row.allowed) {
      rolePermissions.add(row.code);
    } else {
      rolePermissions.delete(row.code);
    }
  });
  
  return Array.from(rolePermissions);
};

/**
 * Update permissions for a role
 * @param {Object} pool - MySQL2 connection pool
 * @param {string} roleCode - Role code
 * @param {Array<string>} permissionCodes - Array of permission codes to assign
 */
export const updateRolePermissions = async (pool, roleCode, permissionCodes = []) => {
  const roleId = await fetchRoleId(pool, roleCode);
  if (!roleId) throw new Error('Role tidak ditemukan');

  // Get permission IDs
  const [permissionRows] = await pool.query(
    'SELECT id, code FROM permissions WHERE code IN (?)',
    [permissionCodes.length ? permissionCodes : ['__none__']]
  );
  const permissionIds = new Set(permissionRows.map(row => row.id));

  // Delete existing permissions
  await pool.query('DELETE FROM role_permissions WHERE role_id = ?', [roleId]);
  
  // Insert new permissions
  if (permissionIds.size === 0) return;
  
  const values = Array.from(permissionIds).map(id => [roleId, id]);
  await pool.query('INSERT INTO role_permissions (role_id, permission_id) VALUES ?', [values]);
};

/**
 * Get all roles with their permissions
 * @param {Object} pool - MySQL2 connection pool
 * @returns {Promise<Array>} Array of role objects with permissions
 */
export const getRolesWithPermissions = async (pool) => {
  const [roles] = await pool.query('SELECT id, code, name FROM roles ORDER BY id ASC');
  const roleIds = roles.map(role => role.id);
  
  if (roleIds.length === 0) return [];
  
  const [rows] = await pool.query(
    `SELECT rp.role_id, p.code
     FROM role_permissions rp
     JOIN permissions p ON p.id = rp.permission_id
     WHERE rp.role_id IN (?)`,
    [roleIds]
  );
  
  // Group permissions by role
  const byRole = new Map();
  rows.forEach(row => {
    const list = byRole.get(row.role_id) || [];
    list.push(row.code);
    byRole.set(row.role_id, list);
  });
  
  return roles.map(role => ({
    code: role.code,
    name: role.name,
    permissions: byRole.get(role.id) || []
  }));
};
