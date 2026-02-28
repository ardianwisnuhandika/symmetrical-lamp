import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { cookie } from '../lib/cookie';

/**
 * Protected Route Component for Super Admin
 * 
 * Ensures only authenticated super admin users can access protected routes.
 * Redirects to login page if user is not authenticated or doesn't have super_admin role.
 * 
 * @param {Object} props - Component props
 * @param {React.ReactNode} props.children - Child components to render if authenticated
 * @returns {React.ReactNode|null} Children if authenticated, null otherwise
 */
export default function SuperAdminProtectedRoute({ children }) {
  const navigate = useNavigate();

  useEffect(() => {
    const admin = cookie.get('super_admin');
    if (!admin || admin.role !== 'super_admin') {
      navigate('/superadmin');
    }
  }, [navigate]);

  const admin = cookie.get('super_admin');
  if (!admin || admin.role !== 'super_admin') return null;

  return children;
}
