# Implementation Plan: Luminous Jepara Super Admin & Analytics Enhancement

## Overview

This implementation plan breaks down the comprehensive Super Admin and Analytics enhancement into discrete, incremental coding tasks. Each task builds on previous work, with testing integrated throughout to catch errors early. The plan follows a logical progression: foundation setup, master data management, system configuration, monitoring and audit, analytics, reporting, backup management, user management enhancements, PJU management upgrades, and role-based dashboards.

## Tasks

- [x] 1. Foundation Setup and Database Migrations
  - Create migration for users table enhancement (add is_suspended column)
  - Install and configure Spatie Activity Log package
  - Install and configure Laravel Excel package
  - Install and configure DomPDF package
  - Set up Pest PHP with faker plugin for property-based testing
  - Create base test factories for all models
  - _Requirements: 7.4, 10.14_

- [-] 2. Master Data Management - Categories
  - [x] 2.1 Create CategoryController with CRUD methods
    - Implement index, create, store, edit, update, destroy methods
    - Add authorization middleware for Super Admin only
    - _Requirements: 1.1, 1.2, 1.3, 1.4_
  
  - [x] 2.2 Create Category form request validators
    - StoreCategoryRequest with unique name validation
    - UpdateCategoryRequest with unique name validation (except current)
    - _Requirements: 1.2_
  
  - [ ] 2.3 Create Category views with DataTables
    - Index view with DataTables for search and pagination
    - Create/edit forms with name, icon, and is_active fields
    - _Requirements: 1.1_
  
  - [ ]* 2.4 Write property test for slug generation
    - **Property 1: Slug Generation Consistency**
    - **Validates: Requirements 1.2, 1.3**
  
  - [ ]* 2.5 Write property test for referential integrity
    - **Property 2: Referential Integrity Protection**
    - **Validates: Requirements 1.4**

- [ ] 3. Master Data Management - PJU Types
  - [ ] 3.1 Create PjuTypeController with CRUD methods
    - Implement index, create, store, edit, update, destroy methods
    - Add authorization middleware for Super Admin only
    - _Requirements: 1.5, 1.6, 1.7, 1.8_
  
  - [ ] 3.2 Create PjuType form request validators
    - StorePjuTypeRequest with unique name validation
    - UpdatePjuTypeRequest with unique name validation (except current)
    - _Requirements: 1.6_
  
  - [ ] 3.3 Create PjuType views with DataTables
    - Index view with DataTables for search and pagination
    - Create/edit forms with name and is_active fields
    - _Requirements: 1.5_

- [ ] 4. Master Data Management - Kecamatan and Desa
  - [ ] 4.1 Create KecamatanController with CRUD methods
    - Implement index, create, store, edit, update, destroy methods
    - Add cascade delete check for associated desas
    - _Requirements: 1.9, 1.10, 1.11_
  
  - [ ] 4.2 Create DesaController with CRUD methods
    - Implement index, create, store, edit, update, destroy methods
    - Add getByKecamatan method for AJAX filtering
    - _Requirements: 1.12, 1.13, 1.14, 1.15_
  
  - [ ] 4.3 Create Kecamatan and Desa views
    - Kecamatan index with DataTables
    - Desa index with kecamatan filter dropdown
    - Create/edit forms for both
    - _Requirements: 1.9, 1.12_
  
  - [ ]* 4.4 Write property test for cascade deletion
    - **Property 3: Cascade Deletion Completeness**
    - **Validates: Requirements 1.11**
  
  - [ ]* 4.5 Write property test for desa filtering
    - **Property 4: Desa Filtering Correctness**
    - **Validates: Requirements 1.13**

- [ ] 5. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.


- [ ] 6. System Settings Management
  - [ ] 6.1 Enhance SystemSetting model with helper methods
    - Add getGroup() method to retrieve settings by group
    - Add setMultiple() method for batch updates
    - _Requirements: 2.1_
  
  - [ ] 6.2 Create SystemSettingController
    - Implement index method to display tabbed interface
    - Implement update method with validation
    - _Requirements: 2.1, 2.8_
  
  - [ ] 6.3 Create system settings views
    - Create tabbed interface with General, Map, Features, Backup tabs
    - Add form fields for each setting group
    - Add JavaScript for tab switching
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_
  
  - [ ] 6.4 Create UpdateSystemSettingsRequest validator
    - Validate coordinate ranges for map settings
    - Validate cron expression format for backup schedule
    - Validate API key formats
    - _Requirements: 2.4, 2.5, 2.7_
  
  - [ ]* 6.5 Write property test for settings persistence
    - **Property 5: Settings Persistence Round-Trip**
    - **Validates: Requirements 2.2, 2.4**
  
  - [ ]* 6.6 Write property test for coordinate validation
    - **Property 6: Coordinate Validation Bounds**
    - **Validates: Requirements 2.5**
  
  - [ ]* 6.7 Write property test for feature toggle visibility
    - **Property 7: Feature Toggle Visibility**
    - **Validates: Requirements 2.6, 8.3**

- [ ] 7. Activity Logging Setup
  - [ ] 7.1 Configure Spatie Activity Log for all models
    - Add LogsActivity trait to Category, PjuType, Kecamatan, Desa models
    - Add LogsActivity trait to User model
    - Add LogsActivity trait to PjuPoint model
    - Configure logged attributes for each model
    - _Requirements: 10.1, 10.2, 10.3, 10.7, 10.8, 10.9, 10.11, 10.12, 10.13_
  
  - [ ] 7.2 Create LoginHistory event listeners
    - Create LoginListener for successful logins
    - Create LogoutListener for logout events
    - Create FailedLoginListener for failed attempts
    - Register listeners in EventServiceProvider
    - _Requirements: 3.12, 10.4, 10.5, 10.6_
  
  - [ ]* 7.3 Write property test for activity log completeness
    - **Property 8: Activity Log Completeness**
    - **Validates: Requirements 2.9, 3.13, 3.14, 3.15, 10.1-10.14**
  
  - [ ]* 7.4 Write property test for login history recording
    - **Property 9: Login History Recording**
    - **Validates: Requirements 3.12, 10.4, 10.5**

- [ ] 8. Monitoring and Audit Logs
  - [ ] 8.1 Create MonitoringController
    - Implement loginHistory method with filtering
    - Implement activityLogs method with filtering
    - Implement responseTimeAnalytics method
    - Implement exportLogs method for Excel export
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10, 3.11_
  
  - [ ] 8.2 Create monitoring views
    - Create tabbed interface for Login History, Activity Logs, Response Time
    - Add filter forms for user, date range, status, model type
    - Add DataTables for login history and activity logs
    - Add export button
    - _Requirements: 3.1, 3.2, 3.6_
  
  - [ ] 8.3 Enhance LoginHistory model with scopes
    - Add scopeSuccessful, scopeFailed, scopeByUser, scopeDateRange
    - _Requirements: 3.3, 3.4, 3.5_
  
  - [ ]* 8.4 Write property test for filter result correctness
    - **Property 10: Filter Result Correctness**
    - **Validates: Requirements 3.3, 3.4, 3.5, 3.7, 3.8, 3.9, 10.15**
  
  - [ ]* 8.5 Write property test for response time calculation
    - **Property 11: Response Time Calculation Accuracy**
    - **Validates: Requirements 3.10**
  
  - [ ]* 8.6 Write property test for export data consistency
    - **Property 12: Export Data Consistency**
    - **Validates: Requirements 3.11, 8.11**

- [ ] 9. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Analytics Service and Dashboard
  - [ ] 10.1 Create AnalyticsService class
    - Implement calculateAssetHealthScore method
    - Implement getRankingWilayah method
    - Implement getCategoryDistribution method
    - Implement getMonthlyFailureTrend method
    - Implement calculatePowerConsumption method
    - _Requirements: 4.2, 4.3, 4.5, 4.6, 4.7, 4.8_
  
  - [ ] 10.2 Create AnalyticsController
    - Implement index method for dashboard view
    - Implement AJAX methods for each chart data endpoint
    - _Requirements: 4.1_
  
  - [ ] 10.3 Create analytics dashboard view
    - Add Asset Health Score gauge chart (Chart.js)
    - Add Ranking Wilayah table and bar chart
    - Add Category Analytics pie chart
    - Add Monthly Failure Trend line chart
    - Add Power Consumption and Cost display
    - Make responsive for mobile
    - _Requirements: 4.1, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8_
  
  - [ ]* 10.4 Write property test for asset health score
    - **Property 13: Asset Health Score Calculation**
    - **Validates: Requirements 4.2**
  
  - [ ]* 10.5 Write property test for ranking wilayah sort
    - **Property 14: Ranking Wilayah Sort Order**
    - **Validates: Requirements 4.3**
  
  - [ ]* 10.6 Write property test for power consumption
    - **Property 15: Power Consumption Calculation**
    - **Validates: Requirements 4.7**
  
  - [ ]* 10.7 Write property test for cost estimation
    - **Property 16: Cost Estimation Formula**
    - **Validates: Requirements 4.8**

- [ ] 11. Report Generation Service
  - [ ] 11.1 Create ReportService class
    - Implement generatePdf method using DomPDF
    - Implement generateExcel method using Laravel Excel
    - Implement saveReportMetadata method
    - Implement renderChartAsImage method for PDF charts
    - _Requirements: 5.4, 5.5, 5.6, 5.7, 5.8, 5.9_
  
  - [ ] 11.2 Create ReportController
    - Implement builder method for report configuration
    - Implement generate method for PDF/Excel generation
    - Implement history method for report list
    - Implement download method for historical reports
    - Implement destroy method for report deletion
    - _Requirements: 5.1, 5.2, 5.3, 5.10, 5.11, 5.12_
  
  - [ ] 11.3 Create report views
    - Create report builder form with date range and section selection
    - Create report history table
    - _Requirements: 5.1, 5.10_
  
  - [ ]* 11.4 Write property test for date range validation
    - **Property 17: Date Range Validation**
    - **Validates: Requirements 5.2**
  
  - [ ]* 11.5 Write property test for report metadata persistence
    - **Property 18: Report Metadata Persistence**
    - **Validates: Requirements 5.9**
  
  - [ ]* 11.6 Write property test for report file deletion
    - **Property 19: Report File Deletion Completeness**
    - **Validates: Requirements 5.12, 6.6**

- [ ] 12. Backup Management
  - [ ] 12.1 Create BackupService class
    - Implement createBackup method for database dump
    - Implement deleteBackup method
    - Implement cleanOldBackups method
    - Implement getBackupPath method
    - _Requirements: 6.1, 6.2, 6.3, 6.6, 6.9_
  
  - [ ] 12.2 Create BackupController
    - Implement index method for backup list
    - Implement create method for manual backup
    - Implement download method
    - Implement destroy method
    - _Requirements: 6.1, 6.4, 6.5, 6.6_
  
  - [ ] 12.3 Create BackupCommand for scheduled backups
    - Create artisan command backup:database
    - Register command in Kernel schedule based on settings
    - _Requirements: 6.7, 6.8_
  
  - [ ] 12.4 Create backup management views
    - Create backup list table with size, date, creator
    - Add manual backup button
    - Add download and delete actions
    - _Requirements: 6.4_
  
  - [ ]* 12.5 Write property test for backup filename format
    - **Property 20: Backup Filename Format**
    - **Validates: Requirements 6.2**
  
  - [ ]* 12.6 Write property test for backup metadata accuracy
    - **Property 21: Backup Metadata Accuracy**
    - **Validates: Requirements 6.3, 6.8**
  
  - [ ]* 12.7 Write property test for backup cleanup threshold
    - **Property 22: Backup Cleanup Threshold**
    - **Validates: Requirements 6.9**

- [ ] 13. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 14. User Management Enhancements
  - [ ] 14.1 Enhance User model
    - Add isOnline method to check last activity within 15 minutes
    - Add lastLogin method to get most recent login history
    - Add loginHistories relationship
    - Add activities relationship
    - _Requirements: 7.1, 7.2, 7.3_
  
  - [ ] 14.2 Enhance UserController
    - Update index to show online status and last login
    - Add suspend method
    - Add activate method
    - Add activityHistory method
    - _Requirements: 7.1, 7.3, 7.4, 7.5, 7.6_
  
  - [ ] 14.3 Update user management views
    - Add online status indicator (green dot)
    - Add last login info display
    - Add suspend/activate toggle button
    - Add activity history link
    - _Requirements: 7.1, 7.3_
  
  - [ ] 14.4 Update authentication to check suspension
    - Modify AuthenticatedSessionController to check is_suspended
    - Display suspension message on login attempt
    - _Requirements: 7.4, 7.8_
  
  - [ ]* 14.5 Write property test for online status determination
    - **Property 23: Online Status Determination**
    - **Validates: Requirements 7.1, 7.2**
  
  - [ ]* 14.6 Write property test for user suspension
    - **Property 24: User Suspension Authentication Block**
    - **Validates: Requirements 7.4, 7.8**
  
  - [ ]* 14.7 Write property test for user activation
    - **Property 25: User Activation Authentication Restore**
    - **Validates: Requirements 7.5**

- [ ] 15. PJU Management Upgrades - Dynamic Dropdowns
  - [ ] 15.1 Update PjuPointController create and edit methods
    - Load active categories for dropdown
    - Load active PJU types for dropdown
    - Load kecamatans for dropdown
    - Check wilayah fields setting
    - _Requirements: 8.1, 8.2, 8.3_
  
  - [ ] 15.2 Update PJU form views
    - Replace hardcoded category options with dynamic dropdown
    - Replace hardcoded type options with dynamic dropdown
    - Add kecamatan dropdown (conditional on settings)
    - Add desa dropdown (conditional on settings, filtered by kecamatan)
    - Add JavaScript for cascading desa dropdown
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_
  
  - [ ]* 15.3 Write property test for active master data dropdown
    - **Property 26: Active Master Data Dropdown Population**
    - **Validates: Requirements 8.1, 8.2**
  
  - [ ]* 15.4 Write property test for cascading dropdown filtering
    - **Property 27: Cascading Dropdown Filtering**
    - **Validates: Requirements 8.4**

- [ ] 16. PJU Management Upgrades - Bulk Operations
  - [ ] 16.1 Add bulk operations to PjuPointController
    - Implement bulkDelete method with transaction
    - Implement bulkVerify method with transaction
    - Add authorization checks
    - _Requirements: 8.6, 8.7, 8.8, 8.9_
  
  - [ ] 16.2 Update PJU index view for bulk operations
    - Add checkboxes for row selection
    - Add "Select All" checkbox
    - Add bulk delete button (Super Admin only)
    - Add bulk verify button (Super Admin only)
    - Add JavaScript for bulk action handling
    - _Requirements: 8.6, 8.8_
  
  - [ ]* 16.3 Write property test for bulk operation completeness
    - **Property 28: Bulk Operation Completeness**
    - **Validates: Requirements 8.7, 8.9**

- [ ] 17. PJU Management Upgrades - Import/Export
  - [ ] 17.1 Add export functionality to PjuPointController
    - Implement export method using Laravel Excel
    - Apply current filters to export
    - _Requirements: 8.10, 8.11_
  
  - [ ] 17.2 Add import functionality to PjuPointController
    - Implement importForm method
    - Implement import method with validation
    - Create Excel import class with validation rules
    - Create template download route
    - _Requirements: 8.12, 8.13, 8.14, 8.15_
  
  - [ ] 17.3 Create import/export views
    - Add export button to PJU index
    - Create import form page
    - Add template download link
    - Add validation error display with row numbers
    - _Requirements: 8.12_
  
  - [ ]* 17.4 Write property test for import validation
    - **Property 29: Import Validation Completeness**
    - **Validates: Requirements 8.13, 8.14**
  
  - [ ]* 17.5 Write property test for import success atomicity
    - **Property 30: Import Success Atomicity**
    - **Validates: Requirements 8.15**

- [ ] 18. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 19. Role-Based Dashboards
  - [ ] 19.1 Enhance DashboardController with role-specific methods
    - Implement superAdminDashboard method
    - Implement adminDishubDashboard method
    - Implement verifikatorDashboard method
    - Update index to route to appropriate dashboard
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6_
  
  - [ ] 19.2 Create Admin Dishub dashboard view
    - Display total PJU count, broken count, verified count
    - Add quick action buttons (Create PJU, View All PJU)
    - Display recent PJU entries table (10 latest)
    - _Requirements: 9.1, 9.2, 9.3_
  
  - [ ] 19.3 Create Verifikator dashboard view
    - Display pending verification count, verified today count
    - Display unverified PJU list with verify buttons
    - Hide administrative features
    - _Requirements: 9.4, 9.5, 9.6, 9.8_
  
  - [ ] 19.4 Add verify action to PjuPointController
    - Implement verify method for single PJU verification
    - Update is_verified, verified_by, verified_at
    - Log verification action
    - _Requirements: 9.7_
  
  - [ ]* 19.5 Write property test for dashboard metric accuracy
    - **Property 31: Dashboard Metric Accuracy**
    - **Validates: Requirements 9.1, 9.3, 9.4**
  
  - [ ]* 19.6 Write property test for verifikator dashboard filtering
    - **Property 32: Verifikator Dashboard Filtering**
    - **Validates: Requirements 9.5**
  
  - [ ]* 19.7 Write property test for verification action completeness
    - **Property 33: Verification Action Completeness**
    - **Validates: Requirements 9.7**

- [ ] 20. Routes and Navigation
  - [ ] 20.1 Add routes for all new features
    - Add master data routes (categories, pju_types, kecamatans, desas)
    - Add system settings routes
    - Add monitoring routes
    - Add analytics routes
    - Add reports routes
    - Add backup routes
    - Add enhanced user management routes
    - Add enhanced PJU management routes
    - Group routes by middleware (auth, role)
    - _Requirements: All_
  
  - [ ] 20.2 Update navigation menu
    - Add Super Admin menu section
    - Add Master Data submenu
    - Add System Settings link
    - Add Monitoring & Audit link
    - Add Analytics Dashboard link
    - Add Reports link
    - Add Backup Management link
    - Update existing menu items
    - Add role-based visibility
    - _Requirements: All_

- [ ] 21. Authorization Policies
  - [ ] 21.1 Create policies for all resources
    - Create CategoryPolicy
    - Create PjuTypePolicy
    - Create KecamatanPolicy
    - Create DesaPolicy
    - Create SystemSettingPolicy
    - Create MonitoringPolicy
    - Create AnalyticsPolicy
    - Create ReportPolicy
    - Create BackupPolicy
    - Update UserPolicy
    - Update PjuPointPolicy
    - _Requirements: All_
  
  - [ ] 21.2 Register policies in AuthServiceProvider
    - Map all policies to models
    - _Requirements: All_

- [ ] 22. Final Integration and Testing
  - [ ] 22.1 Run all property-based tests
    - Execute all property tests with 100 iterations
    - Verify all properties pass
    - _Requirements: All_
  
  - [ ]* 22.2 Run all unit tests
    - Execute full test suite
    - Verify all tests pass
    - _Requirements: All_
  
  - [ ] 22.3 Manual integration testing
    - Test complete workflows for each role
    - Test all CRUD operations
    - Test all bulk operations
    - Test all import/export operations
    - Test all report generation
    - Test all backup operations
    - Verify responsive design on mobile
    - _Requirements: All_

- [ ] 23. Final Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional property-based tests and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation at logical breaks
- Property tests validate universal correctness properties with 100 iterations each
- Unit tests validate specific examples, edge cases, and integration points
- All database operations use transactions for data integrity
- All file operations include error handling
- All user actions are logged for audit trail
- Authorization is enforced at controller and policy levels
