# Changelog

All notable changes to the Admin System will be documented in this file.

## [1.0.0] - 2024

### Added - Backend
- ✅ Complete database schema with 8 tables (admins, roles, permissions, etc.)
- ✅ Security initialization with default roles and permissions
- ✅ Permission checking library with flexible access control
- ✅ Authorization middleware with factory pattern
- ✅ Admin CRUD routes (register, update, delete, list)
- ✅ Permission management routes
- ✅ Settings management routes
- ✅ Activity logging system
- ✅ Server health monitoring
- ✅ Password hashing and validation
- ✅ Login rate limiting and max attempts
- ✅ Session management
- ✅ Maintenance mode
- ✅ Integration example with detailed comments

### Added - Frontend
- ✅ SuperAdminLogin component with captcha verification
- ✅ BadanGiziLogin component with captcha verification
- ✅ SuperAdminProtectedRoute for route guarding
- ✅ BadanGiziProtectedRoute for route guarding
- ✅ adminApi.js - Complete API client library
- ✅ cookie.js - Cookie management utility
- ✅ sessionTimeout.js - Auto-logout hook
- ✅ export.js - Data export to CSV/Excel/PDF
- ✅ Frontend documentation (README.md)
- ✅ Package.json with peer dependencies

### Features
- 🔐 Role-Based Access Control (RBAC)
  - Super Admin: Full system access
  - Badan Gizi: Limited monitoring and approval
  - Admin SPPG: Own data only

- 🛡️ Security
  - Password hashing with custom algorithm
  - Login rate limiting (IP-based)
  - Max login attempts with auto-lock
  - Session timeout with activity tracking
  - Maintenance mode for system updates

- 📊 Monitoring
  - Activity logs with IP tracking
  - Server health metrics
  - Database connection monitoring
  - Query performance tracking

- 📝 Reports & Requests
  - Laporan (issue reporting)
  - Request data (data change requests)
  - Status tracking (pending, processed, completed)

- ⚙️ Configuration
  - Global settings management
  - Announcements (global, super admin, SPPG)
  - Password policy enforcement
  - Session timeout configuration

- 📤 Export
  - CSV export with UTF-8 BOM
  - Excel export with styling
  - PDF export with print dialog

### Architecture Decisions
- **Factory Pattern**: All route handlers accept `pool` parameter for database flexibility
- **Modular Design**: Each component is independent and can be used separately
- **No Hardcoded Dependencies**: All database connections passed as parameters
- **Comprehensive Documentation**: JSDoc comments on all functions
- **Clean Code**: Removed SPPG-specific logic, kept generic structure

### Not Included
- ❌ Dashboard components (too application-specific)
- ❌ SPPG-specific business logic
- ❌ Map/GIS components
- ❌ Beneficiary category management
- ❌ Wilayah (region) data

These were intentionally excluded as they are specific to the SPPG application. 
Users should build their own dashboards using the provided API client and utilities.

### Migration Notes
When integrating into your application:

1. **Backend**: 
   - Import database pool from your config
   - Pass pool to factory functions
   - Mount routes on your Express app

2. **Frontend**:
   - Copy components to your src folder
   - Update API_URL in adminApi.js
   - Build your own dashboard using adminApi

3. **Database**:
   - Run schema.sql to create tables
   - Bootstrap will create default admin on first run
   - Change default password immediately

### Known Issues
- None reported yet

### Future Enhancements
- [ ] Two-factor authentication (2FA)
- [ ] Email notifications
- [ ] Password reset via email
- [ ] Audit trail export
- [ ] Role hierarchy
- [ ] Permission groups
- [ ] API rate limiting per user
- [ ] WebSocket for real-time updates

---

## Version History

### v1.0.0 (Initial Release)
Complete extraction of admin system from SPPG application into standalone, plug-and-play module.
