import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { cookie } from '../lib/cookie';
import { adminApi } from '../lib/adminApi';

/**
 * Protected Route Component for Badan Gizi
 * 
 * Ensures only authenticated badan_gizi users can access protected routes.
 * Also checks for maintenance mode and redirects if active.
 * Redirects to login page if user is not authenticated or doesn't have badan_gizi role.
 * 
 * @param {Object} props - Component props
 * @param {React.ReactNode} props.children - Child components to render if authenticated
 * @returns {React.ReactNode|null} Children if authenticated, null otherwise
 */
export default function BadanGiziProtectedRoute({ children }) {
  const navigate = useNavigate();

  useEffect(() => {
    const admin = cookie.get('badan_gizi');
    if (!admin || admin.role !== 'badan_gizi') {
      navigate('/badan-gizi');
      return;
    }
    
    // Check maintenance mode
    let active = true;
    adminApi.getSettings()
      .then((settings) => {
        if (!active) return;
        const maintenance = String(settings?.maintenance_mode || '').toLowerCase() === 'true';
        if (maintenance) {
          cookie.remove('badan_gizi');
          navigate('/badan-gizi');
        }
      })
      .catch(() => {});
    return () => { active = false; };
  }, [navigate]);

  const admin = cookie.get('badan_gizi');
  if (!admin || admin.role !== 'badan_gizi') return null;

  return children;
}
