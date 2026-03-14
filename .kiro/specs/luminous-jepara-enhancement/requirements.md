# Requirements Document

## Introduction

This document specifies the requirements for enhancing the Luminous Jepara PJU (street lighting) monitoring system with comprehensive Super Admin features and Analytics Dashboard. The system is built on Laravel 10 and manages street lighting infrastructure across Jepara regency, including PJU (street lights), Rambu (traffic signs), RPPJ (public street lighting), and Cermin (mirrors).

The enhancement focuses on providing Super Admin users with powerful tools for master data management, system configuration, monitoring, analytics, reporting, and backup management, while also improving the PJU management interface for all user roles.

## Glossary

- **System**: The Luminous Jepara PJU monitoring application
- **Super_Admin**: User role with full system access and administrative privileges
- **Admin_Dishub**: User role for Department of Transportation administrators with PJU CRUD access
- **Verifikator**: User role for verification staff who can verify PJU status changes
- **PJU**: Penerangan Jalan Umum (public street lighting)
- **Category**: Classification of infrastructure (PJU, Rambu, RPPJ, Cermin)
- **PJU_Type**: Type of lighting technology (Sonte, LED, Kalipucang)
- **Kecamatan**: District administrative division
- **Desa**: Village administrative division
- **Asset_Health_Score**: Calculated percentage of functional infrastructure
- **Activity_Log**: Audit trail record of system changes
- **Login_History**: Record of user authentication events
- **Backup_File**: Database backup stored in storage/backups directory
- **DataTables**: jQuery plugin for interactive table display
- **Chart_JS**: JavaScript library for data visualization
- **Spatie_Activity_Log**: Laravel package for audit logging

## Requirements

### Requirement 1: Master Data Management

**User Story:** As a Super Admin, I want to manage master data for categories, PJU types, districts, and villages, so that the system can adapt to changing infrastructure classifications and administrative boundaries.

#### Acceptance Criteria

1. WHEN a Super Admin accesses the categories management page, THE System SHALL display all categories with name, slug, icon, active status, and action buttons
2. WHEN a Super Admin creates a new category, THE System SHALL validate the name is unique and automatically generate a slug
3. WHEN a Super Admin updates a category name, THE System SHALL automatically regenerate the slug
4. WHEN a Super Admin deletes a category, THE System SHALL prevent deletion if PJU points reference it
5. WHEN a Super Admin accesses the PJU types management page, THE System SHALL display all types with name, slug, active status, and action buttons
6. WHEN a Super Admin creates a new PJU type, THE System SHALL validate the name is unique and automatically generate a slug
7. WHEN a Super Admin updates a PJU type name, THE System SHALL automatically regenerate the slug
8. WHEN a Super Admin deletes a PJU type, THE System SHALL prevent deletion if PJU points reference it
9. WHEN a Super Admin accesses the kecamatan management page, THE System SHALL display all districts with DataTables search and pagination
10. WHEN a Super Admin creates a new kecamatan, THE System SHALL validate the name is unique
11. WHEN a Super Admin deletes a kecamatan, THE System SHALL cascade delete all associated desas
12. WHEN a Super Admin accesses the desa management page, THE System SHALL display all villages with their parent kecamatan
13. WHEN a Super Admin filters desa by kecamatan, THE System SHALL display only villages in that district
14. WHEN a Super Admin creates a new desa, THE System SHALL require a valid kecamatan_id
15. WHEN a Super Admin deletes a desa, THE System SHALL set kecamatan_id to null for all associated PJU points

### Requirement 2: System Settings Management

**User Story:** As a Super Admin, I want to configure system-wide settings through a tabbed interface, so that I can control application behavior without modifying code.

#### Acceptance Criteria

1. WHEN a Super Admin accesses system settings, THE System SHALL display four tabs: General, Map, Features, and Backup
2. WHEN a Super Admin updates the application name in General settings, THE System SHALL persist the value and display it in the application header
3. WHEN a Super Admin toggles maintenance mode in General settings, THE System SHALL enable or disable public access
4. WHEN a Super Admin updates map API keys in Map settings, THE System SHALL validate the format and persist the values
5. WHEN a Super Admin updates default map center coordinates in Map settings, THE System SHALL validate latitude and longitude ranges
6. WHEN a Super Admin toggles wilayah fields in Feature settings, THE System SHALL show or hide kecamatan and desa fields in PJU forms
7. WHEN a Super Admin configures auto backup schedule in Backup settings, THE System SHALL validate cron expression format
8. WHEN a Super Admin saves any settings tab, THE System SHALL validate all fields and display success or error messages
9. WHEN settings are updated, THE System SHALL log the change in activity logs with old and new values

### Requirement 3: Monitoring and Audit Logs

**User Story:** As a Super Admin, I want to monitor user activity and system changes through comprehensive audit logs, so that I can track security events and troubleshoot issues.

#### Acceptance Criteria

1. WHEN a Super Admin accesses monitoring page, THE System SHALL display three tabs: Login History, Activity Logs, and Response Time Analytics
2. WHEN a Super Admin views login history, THE System SHALL display user, timestamp, IP address, user agent, and status
3. WHEN a Super Admin filters login history by user, THE System SHALL display only that user's login events
4. WHEN a Super Admin filters login history by date range, THE System SHALL display only events within that range
5. WHEN a Super Admin filters login history by status, THE System SHALL display only successful or failed login attempts
6. WHEN a Super Admin views activity logs, THE System SHALL display who changed what, when, and the before/after values
7. WHEN a Super Admin filters activity logs by user, THE System SHALL display only that user's activities
8. WHEN a Super Admin filters activity logs by date range, THE System SHALL display only activities within that range
9. WHEN a Super Admin filters activity logs by model type, THE System SHALL display only changes to that model
10. WHEN a Super Admin views response time analytics, THE System SHALL calculate average time from PJU input to verification
11. WHEN a Super Admin exports logs to Excel, THE System SHALL generate a file with all filtered records
12. WHEN any user logs in, THE System SHALL automatically record the event in login_histories table
13. WHEN any user modifies PJU data, THE System SHALL automatically log the change using Spatie Activity Log
14. WHEN any Super Admin modifies user accounts, THE System SHALL automatically log the change
15. WHEN any Super Admin modifies system settings, THE System SHALL automatically log the change

### Requirement 4: Analytics Dashboard

**User Story:** As a Super Admin, I want to visualize infrastructure health and performance metrics through interactive charts, so that I can make data-driven decisions about maintenance priorities.

#### Acceptance Criteria

1. WHEN a Super Admin accesses the analytics dashboard, THE System SHALL display Asset Health Score as a percentage gauge chart
2. WHEN calculating Asset Health Score, THE System SHALL divide functional PJU points by total PJU points and multiply by 100
3. WHEN a Super Admin views ranking wilayah, THE System SHALL display a table of areas sorted by number of broken lights descending
4. WHEN a Super Admin views ranking wilayah chart, THE System SHALL display a horizontal bar chart of top 10 areas
5. WHEN a Super Admin views category analytics, THE System SHALL display a pie chart showing distribution of PJU, Rambu, RPPJ, and Cermin
6. WHEN a Super Admin views monthly failure trend, THE System SHALL display a line chart of broken lights per month for the last 12 months
7. WHEN a Super Admin views total power consumption, THE System SHALL calculate wattage from all normal status lights
8. WHEN a Super Admin views estimated cost, THE System SHALL multiply total power consumption by configurable electricity rate
9. WHEN analytics data is loaded, THE System SHALL use Chart.js for all visualizations
10. WHEN analytics page is viewed on mobile, THE System SHALL display responsive charts that fit the screen

### Requirement 5: Reports and Export

**User Story:** As a Super Admin, I want to generate comprehensive reports with charts and tables in PDF or Excel format, so that I can share infrastructure status with stakeholders.

#### Acceptance Criteria

1. WHEN a Super Admin accesses the report builder, THE System SHALL display options to select date range and data sections
2. WHEN a Super Admin selects report period, THE System SHALL validate start date is before end date
3. WHEN a Super Admin selects data sections, THE System SHALL allow multiple selections including summary stats, charts, and detailed tables
4. WHEN a Super Admin generates PDF report, THE System SHALL use DomPDF to create a formatted document with logo placeholder
5. WHEN a PDF report is generated, THE System SHALL include selected charts rendered as images
6. WHEN a PDF report is generated, THE System SHALL include selected data tables with proper formatting
7. WHEN a Super Admin exports to Excel, THE System SHALL use Laravel Excel to create a workbook with multiple sheets
8. WHEN an Excel export is generated, THE System SHALL include one sheet per data section
9. WHEN a report is generated, THE System SHALL save metadata to backup_histories table
10. WHEN a Super Admin views report history, THE System SHALL display list of generated reports with size, date, and creator
11. WHEN a Super Admin downloads a historical report, THE System SHALL serve the file from storage
12. WHEN a Super Admin deletes a historical report, THE System SHALL remove the file from storage and database record

### Requirement 6: Backup Management

**User Story:** As a Super Admin, I want to create and manage database backups with automated scheduling, so that I can protect against data loss.

#### Acceptance Criteria

1. WHEN a Super Admin clicks manual backup button, THE System SHALL create a database dump in storage/backups directory
2. WHEN a backup is created, THE System SHALL name the file with timestamp format YYYY-MM-DD_HH-MM-SS_database.sql
3. WHEN a backup is created, THE System SHALL record metadata in backup_histories table including size, creator, and timestamp
4. WHEN a Super Admin views backup list, THE System SHALL display all backup files with size, date, and creator
5. WHEN a Super Admin downloads a backup file, THE System SHALL serve the SQL file with appropriate headers
6. WHEN a Super Admin deletes a backup file, THE System SHALL remove both the file and database record
7. WHEN auto backup is enabled in settings, THE System SHALL run scheduled backup according to configured cron expression
8. WHEN scheduled backup runs, THE System SHALL create backup file and record with creator set to "System"
9. WHEN backup storage exceeds configured limit, THE System SHALL delete oldest backups automatically
10. WHEN a backup operation fails, THE System SHALL log the error and notify Super Admin

### Requirement 7: User Management Upgrade

**User Story:** As a Super Admin, I want enhanced user management features including online status and activity tracking, so that I can monitor user behavior and manage access effectively.

#### Acceptance Criteria

1. WHEN a Super Admin views user list, THE System SHALL display online status indicator based on recent login_histories
2. WHEN determining online status, THE System SHALL consider users active if last activity was within 15 minutes
3. WHEN a Super Admin views user details, THE System SHALL display last login timestamp and IP address
4. WHEN a Super Admin suspends a user, THE System SHALL set is_suspended flag and prevent that user from logging in
5. WHEN a Super Admin activates a suspended user, THE System SHALL clear is_suspended flag and allow login
6. WHEN a Super Admin views user activity history, THE System SHALL display filtered activity logs for that user
7. WHEN a Super Admin clicks activity history link, THE System SHALL navigate to activity logs page with user filter applied
8. WHEN a suspended user attempts login, THE System SHALL reject authentication and display suspension message

### Requirement 8: PJU Management Upgrade

**User Story:** As a user with any role, I want improved PJU management with dynamic dropdowns and bulk operations, so that I can work more efficiently with infrastructure data.

#### Acceptance Criteria

1. WHEN any user accesses PJU create form, THE System SHALL populate category dropdown from active categories
2. WHEN any user accesses PJU create form, THE System SHALL populate type dropdown from active PJU types
3. WHEN wilayah fields are enabled in settings, THE System SHALL display kecamatan and desa dropdowns in PJU form
4. WHEN a user selects a kecamatan, THE System SHALL filter desa dropdown to show only villages in that district
5. WHEN any user accesses PJU edit form, THE System SHALL pre-select current category, type, kecamatan, and desa
6. WHEN a Super Admin selects multiple PJU points, THE System SHALL enable bulk delete action
7. WHEN a Super Admin executes bulk delete, THE System SHALL delete all selected PJU points and log the action
8. WHEN a Super Admin selects multiple unverified PJU points, THE System SHALL enable bulk verify action
9. WHEN a Super Admin executes bulk verify, THE System SHALL update status to verified for all selected points
10. WHEN any user applies filters to PJU list, THE System SHALL enable export filtered data to Excel button
11. WHEN any user exports filtered PJU data, THE System SHALL generate Excel file with only filtered records
12. WHEN a Super Admin accesses import page, THE System SHALL provide template download link
13. WHEN a Super Admin uploads Excel file for import, THE System SHALL validate all rows against database constraints
14. WHEN import validation fails, THE System SHALL display error messages with row numbers
15. WHEN import validation succeeds, THE System SHALL insert all records and log the action

### Requirement 9: Simplified Role-Based Dashboards

**User Story:** As an Admin Dishub or Verifikator, I want a simplified dashboard focused on my role's responsibilities, so that I can quickly access relevant features without distraction.

#### Acceptance Criteria

1. WHEN an Admin Dishub logs in, THE System SHALL display dashboard with total PJU count, broken count, and verified count
2. WHEN an Admin Dishub views dashboard, THE System SHALL display quick action buttons for Create PJU and View All PJU
3. WHEN an Admin Dishub views dashboard, THE System SHALL display recent PJU entries table with 10 latest records
4. WHEN a Verifikator logs in, THE System SHALL display dashboard with pending verification count and verified today count
5. WHEN a Verifikator views dashboard, THE System SHALL display PJU list filtered to show only unverified points
6. WHEN a Verifikator views PJU list, THE System SHALL display verify button next to each unverified point
7. WHEN a Verifikator clicks verify button, THE System SHALL update status to verified and log the action
8. WHEN a Verifikator views dashboard, THE System SHALL hide administrative features like settings and backups

### Requirement 10: Automatic Activity Logging

**User Story:** As a system administrator, I want all critical actions automatically logged, so that I have a complete audit trail without manual intervention.

#### Acceptance Criteria

1. WHEN any user creates a PJU point, THE System SHALL log the action with model type, user, and new values
2. WHEN any user updates a PJU point, THE System SHALL log the action with model type, user, old values, and new values
3. WHEN any user deletes a PJU point, THE System SHALL log the action with model type, user, and deleted values
4. WHEN any user logs in successfully, THE System SHALL record timestamp, IP address, user agent, and status in login_histories
5. WHEN any user login fails, THE System SHALL record timestamp, IP address, user agent, and failure reason in login_histories
6. WHEN any user logs out, THE System SHALL update last activity timestamp in login_histories
7. WHEN a Super Admin creates a user, THE System SHALL log the action with new user details
8. WHEN a Super Admin updates a user, THE System SHALL log the action with old and new values
9. WHEN a Super Admin deletes a user, THE System SHALL log the action with deleted user details
10. WHEN a Super Admin updates system settings, THE System SHALL log the action with setting key, old value, and new value
11. WHEN a Super Admin creates master data, THE System SHALL log the action with model type and new values
12. WHEN a Super Admin updates master data, THE System SHALL log the action with model type, old values, and new values
13. WHEN a Super Admin deletes master data, THE System SHALL log the action with model type and deleted values
14. WHEN Spatie Activity Log records an action, THE System SHALL include causer_id, subject_id, subject_type, and properties
15. WHEN activity logs are queried, THE System SHALL support filtering by causer, subject type, and date range
