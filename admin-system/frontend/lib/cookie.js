/**
 * Cookie Management Utility
 * 
 * Provides functions to set, get, and remove cookies with JSON serialization support.
 * Used for storing admin session data in the browser.
 */

const COOKIE_EXPIRY_DAYS = 7;

export const cookie = {
  /**
   * Set a cookie with a value that expires after specified days
   * @param {string} name - Cookie name
   * @param {any} value - Cookie value (will be JSON stringified)
   * @param {number} days - Number of days until expiration (default: 7)
   */
  set(name, value, days = COOKIE_EXPIRY_DAYS) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
  },

  /**
   * Set a cookie with a value that expires after specified minutes
   * @param {string} name - Cookie name
   * @param {any} value - Cookie value (will be JSON stringified)
   * @param {number} minutes - Number of minutes until expiration (default: 60)
   */
  setWithMinutes(name, value, minutes = 60) {
    const safeMinutes = Math.max(1, Number(minutes) || 60);
    const expires = new Date();
    expires.setTime(expires.getTime() + safeMinutes * 60 * 1000);
    document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
  },

  /**
   * Get a cookie value by name
   * @param {string} name - Cookie name
   * @returns {any|null} Parsed cookie value or null if not found
   */
  get(name) {
    const nameEQ = `${name}=`;
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      let cookie = cookies[i];
      while (cookie.charAt(0) === ' ') cookie = cookie.substring(1);
      if (cookie.indexOf(nameEQ) === 0) {
        try {
          return JSON.parse(decodeURIComponent(cookie.substring(nameEQ.length)));
        } catch {
          return null;
        }
      }
    }
    return null;
  },

  /**
   * Remove a cookie by name
   * @param {string} name - Cookie name to remove
   */
  remove(name) {
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
  }
};
