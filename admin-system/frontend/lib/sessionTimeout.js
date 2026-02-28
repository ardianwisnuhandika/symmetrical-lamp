/**
 * Session Timeout Hook
 * 
 * Automatically logs out users after a period of inactivity.
 * Tracks user activity (mouse, keyboard, touch events) and refreshes the session cookie.
 * When inactivity exceeds the timeout period, triggers the onExpire callback.
 */

import { useEffect } from 'react';
import { cookie } from './cookie';

const getTimeoutMinutes = (value) => {
  const parsed = Number(value);
  if (!Number.isFinite(parsed) || parsed <= 0) return 60;
  return parsed;
};

/**
 * React hook for managing session timeout based on user activity
 * 
 * @param {Object} options - Configuration options
 * @param {string} options.cookieName - Name of the cookie storing session data
 * @param {Object} options.admin - Admin user object
 * @param {number} options.timeoutMinutes - Inactivity timeout in minutes
 * @param {Function} options.onExpire - Callback function when session expires
 */
export const useSessionTimeout = ({ cookieName, admin, timeoutMinutes, onExpire }) => {
  useEffect(() => {
    if (!cookieName || !admin) return;

    const minutes = getTimeoutMinutes(timeoutMinutes);
    const timeoutMs = minutes * 60 * 1000;
    const activityKey = `session_activity_${cookieName}`;

    // Update last activity timestamp and refresh cookie
    const touch = () => {
      const now = Date.now();
      localStorage.setItem(activityKey, String(now));
      cookie.setWithMinutes(cookieName, admin, minutes);
    };

    // Initial touch
    touch();

    // Activity event handler
    const handler = () => touch();
    
    // Listen to user activity events
    const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'];
    events.forEach(event => window.addEventListener(event, handler, { passive: true }));

    // Check for inactivity every 30 seconds
    const interval = setInterval(() => {
      const lastActivity = Number(localStorage.getItem(activityKey) || 0);
      if (lastActivity && Date.now() - lastActivity > timeoutMs) {
        cookie.remove(cookieName);
        localStorage.removeItem(activityKey);
        if (typeof onExpire === 'function') onExpire();
      }
    }, 30000);

    // Cleanup
    return () => {
      events.forEach(event => window.removeEventListener(event, handler));
      clearInterval(interval);
    };
  }, [cookieName, admin, timeoutMinutes, onExpire]);
};
