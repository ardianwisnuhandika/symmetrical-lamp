/**
 * Admin System - API Client
 * Handles all API calls to the admin backend
 */

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:3001';

/**
 * Get admin ID from cookie
 * @param {string} cookieName - Cookie name to extract admin ID from
 * @returns {number|null} Admin ID or null
 */
const getAdminId = (cookieName = 'super_admin') => {
  try {
    const cookies = document.cookie.split(';');
    const cookie = cookies.find(c => c.trim().startsWith(`${cookieName}=`));
    if (!cookie) return null;
    
    const value = cookie.split('=')[1];
    const data = JSON.parse(decodeURIComponent(value));
    return data?.id || null;
  } catch {
    return null;
  }
};

/**
 * Make API request with admin authentication
 * @param {string} endpoint - API endpoint
 * @param {Object} options - Fetch options
 * @param {string} cookieName - Cookie name for admin ID
 * @returns {Promise<any>} Response data
 */
const apiRequest = async (endpoint, options = {}, cookieName = 'super_admin') => {
  const adminId = getAdminId(cookieName);
  
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
  };

  if (adminId) {
    headers['x-admin-id'] = adminId;
  }

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    headers,
  });

  if (!response.ok) {
    const error = await response.json().catch(() => ({ error: 'Request failed' }));
    throw new Error(error.error || `HTTP ${response.status}`);
  }

  return response.json();
};

// ============================================
// Authentication
// ============================================

export const login = async (username, password) => {
  return apiRequest('/api/admin/login', {
    method: 'POST',
    body: JSON.stringify({ username, password }),
  });
};

export const getMyProfile = async (cookieName = 'super_admin') => {
  return apiRequest('/api/admin/me', {}, cookieName);
};

// ============================================
// Admin Management
// ============================================

export const getAdmins = async (cookieName = 'super_admin') => {
  return apiRequest('/api/admin/list', {}, cookieName);
};

export const register = async (data, cookieName = 'super_admin') => {
  return apiRequest('/api/admin/register', {
    method: 'POST',
    body: JSON.stringify(data),
  }, cookieName);
};

export const updateAdmin = async (id, data, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/${id}`, {
    method: 'PUT',
    body: JSON.stringify(data),
  }, cookieName);
};

export const deleteAdmin = async (id, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/${id}`, {
    method: 'DELETE',
  }, cookieName);
};

export const changePassword = async (id, password, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/${id}/password`, {
    method: 'PUT',
    body: JSON.stringify({ password }),
  }, cookieName);
};

// ============================================
// Permissions
// ============================================

export const getAllPermissions = async (cookieName = 'super_admin') => {
  return apiRequest('/api/permissions/catalog', {}, cookieName);
};

export const getRolesWithPermissions = async (cookieName = 'super_admin') => {
  return apiRequest('/api/permissions/roles', {}, cookieName);
};

export const updateRolePermissions = async (roleCode, permissions, cookieName = 'super_admin') => {
  return apiRequest(`/api/permissions/roles/${roleCode}`, {
    method: 'PUT',
    body: JSON.stringify({ permissions }),
  }, cookieName);
};

// ============================================
// Settings
// ============================================

export const getSettings = async () => {
  return apiRequest('/api/settings');
};

export const updateSettings = async (settings, cookieName = 'super_admin') => {
  return apiRequest('/api/settings', {
    method: 'PUT',
    body: JSON.stringify({ settings }),
  }, cookieName);
};

export const updateAnnouncement = async (scope, message, cookieName = 'super_admin') => {
  return apiRequest('/api/settings/announcement', {
    method: 'PUT',
    body: JSON.stringify({ scope, message }),
  }, cookieName);
};

export const getStats = async () => {
  return apiRequest('/api/settings/stats');
};

export const getServerHealth = async () => {
  return apiRequest('/api/settings/server-health');
};

// ============================================
// Activity Logs
// ============================================

export const getLogs = async (limit = 100, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/logs?limit=${limit}`, {}, cookieName);
};

// ============================================
// Reports (Laporan)
// ============================================

export const getLaporan = async (cookieName = 'super_admin') => {
  return apiRequest('/api/admin/laporan', {}, cookieName);
};

export const createLaporan = async (data, cookieName = 'super_admin') => {
  return apiRequest('/api/admin/laporan', {
    method: 'POST',
    body: JSON.stringify(data),
  }, cookieName);
};

export const updateLaporanStatus = async (id, status, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/laporan/${id}/status`, {
    method: 'PUT',
    body: JSON.stringify({ status }),
  }, cookieName);
};

// ============================================
// Data Requests
// ============================================

export const getRequestData = async (cookieName = 'super_admin') => {
  return apiRequest('/api/admin/request-data', {}, cookieName);
};

export const createRequestData = async (data, cookieName = 'super_admin') => {
  return apiRequest('/api/admin/request-data', {
    method: 'POST',
    body: JSON.stringify(data),
  }, cookieName);
};

export const updateRequestStatus = async (id, status, cookieName = 'super_admin') => {
  return apiRequest(`/api/admin/request-data/${id}/status`, {
    method: 'PUT',
    body: JSON.stringify({ status }),
  }, cookieName);
};

// Export all as adminApi object
export const adminApi = {
  login,
  getMyProfile,
  getAdmins,
  register,
  updateAdmin,
  deleteAdmin,
  changePassword,
  getAllPermissions,
  getRolesWithPermissions,
  updateRolePermissions,
  getSettings,
  updateSettings,
  updateAnnouncement,
  getStats,
  getServerHealth,
  getLogs,
  getLaporan,
  createLaporan,
  updateLaporanStatus,
  getRequestData,
  createRequestData,
  updateRequestStatus,
};

export default adminApi;
