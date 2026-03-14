# Design Document

## Overview

The Luminous Jepara Super Admin & Analytics Enhancement extends the existing Laravel 10 PJU monitoring system with comprehensive administrative features, analytics capabilities, and improved data management. The design leverages Laravel's built-in features, established packages (Spatie Permission, Spatie Activity Log), and modern frontend libraries (DataTables, Chart.js) to create a robust, maintainable solution.

The architecture follows Laravel MVC patterns with clear separation of concerns: Controllers handle HTTP requests and business logic, Models represent database entities with relationships, Views render UI using Blade templates, and Middleware/Policies enforce authorization. The design emphasizes code reusability through service classes for complex operations like backup management and report generation.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Presentation Layer                    │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Blade      │  │  DataTables  │  │   Chart.js   │      │
│  │  Templates   │  │   (jQuery)   │  │ (Analytics)  │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      Application Layer                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Controllers  │  │  Middleware  │  │   Policies   │      │
│  │              │  │  (Auth/Role) │  │ (Authorization)│    │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Services   │  │   Requests   │  │   Resources  │      │
│  │  (Business)  │  │ (Validation) │  │    (API)     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                        Domain Layer                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Models     │  │ Relationships│  │   Scopes     │      │
│  │  (Eloquent)  │  │              │  │              │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      Infrastructure Layer                    │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Database   │  │ File Storage │  │   Cache      │      │
│  │   (MySQL)    │  │   (Local)    │  │   (Redis)    │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
```

### Component Interaction Flow

```
User Request → Route → Middleware (Auth/Permission) → Controller
                                                          ↓
                                                    Service Layer
                                                          ↓
                                                    Model/Database
                                                          ↓
                                                    Response/View
                                                          ↓
                                                    Activity Log
```

## Components and Interfaces

### 1. Master Data Management Components

#### CategoryController
```php
class CategoryController extends Controller
{
    public function index(): View
    public function create(): View
    public function store(StoreCategoryRequest $request): RedirectResponse
    public function edit(Category $category): View
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    public function destroy(Category $category): RedirectResponse
}
```

#### PjuTypeController
```php
class PjuTypeController extends Controller
{
    public function index(): View
    public function create(): View
    public function store(StorePjuTypeRequest $request): RedirectResponse
    public function edit(PjuType $pjuType): View
    public function update(UpdatePjuTypeRequest $request, PjuType $pjuType): RedirectResponse
    public function destroy(PjuType $pjuType): RedirectResponse
}
```

#### KecamatanController
```php
class KecamatanController extends Controller
{
    public function index(): View
    public function create(): View
    public function store(StoreKecamatanRequest $request): RedirectResponse
    public function edit(Kecamatan $kecamatan): View
    public function update(UpdateKecamatanRequest $request, Kecamatan $kecamatan): RedirectResponse
    public function destroy(Kecamatan $kecamatan): RedirectResponse
}
```

#### DesaController
```php
class DesaController extends Controller
{
    public function index(Request $request): View
    public function create(): View
    public function store(StoreDesaRequest $request): RedirectResponse
    public function edit(Desa $desa): View
    public function update(UpdateDesaRequest $request, Desa $desa): RedirectResponse
    public function destroy(Desa $desa): RedirectResponse
    public function getByKecamatan(Kecamatan $kecamatan): JsonResponse
}
```

### 2. System Settings Components

#### SystemSettingController
```php
class SystemSettingController extends Controller
{
    public function index(): View
    public function update(UpdateSystemSettingsRequest $request): RedirectResponse
}
```

#### SystemSetting Model (Enhanced)
```php
class SystemSetting extends Model
{
    public static function get(string $key, mixed $default = null): mixed
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general', ?string $description = null): self
    public static function getGroup(string $group): Collection
    public static function setMultiple(array $settings): void
}
```

### 3. Monitoring and Audit Components

#### MonitoringController
```php
class MonitoringController extends Controller
{
    public function loginHistory(Request $request): View
    public function activityLogs(Request $request): View
    public function responseTimeAnalytics(): View
    public function exportLogs(Request $request): BinaryFileResponse
}
```

#### LoginHistory Model
```php
class LoginHistory extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'status', 'login_at', 'logout_at'];
    
    public function user(): BelongsTo
    public function scopeSuccessful($query): Builder
    public function scopeFailed($query): Builder
    public function scopeByUser($query, int $userId): Builder
    public function scopeDateRange($query, string $start, string $end): Builder
}
```

### 4. Analytics Components

#### AnalyticsController
```php
class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService) {}
    
    public function index(): View
    public function getAssetHealthScore(): JsonResponse
    public function getRankingWilayah(): JsonResponse
    public function getCategoryAnalytics(): JsonResponse
    public function getMonthlyFailureTrend(): JsonResponse
    public function getPowerConsumption(): JsonResponse
}
```

#### AnalyticsService
```php
class AnalyticsService
{
    public function calculateAssetHealthScore(): float
    public function getRankingWilayah(int $limit = 10): Collection
    public function getCategoryDistribution(): array
    public function getMonthlyFailureTrend(int $months = 12): array
    public function calculatePowerConsumption(): array
}
```

### 5. Reports and Export Components

#### ReportController
```php
class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}
    
    public function builder(): View
    public function generate(GenerateReportRequest $request): BinaryFileResponse
    public function history(): View
    public function download(BackupHistory $report): BinaryFileResponse
    public function destroy(BackupHistory $report): RedirectResponse
}
```

#### ReportService
```php
class ReportService
{
    public function generatePdf(array $options): string
    public function generateExcel(array $options): string
    public function saveReportMetadata(string $filename, string $type, int $size): BackupHistory
    public function renderChartAsImage(string $chartType, array $data): string
}
```

### 6. Backup Management Components

#### BackupController
```php
class BackupController extends Controller
{
    public function __construct(private BackupService $backupService) {}
    
    public function index(): View
    public function create(): RedirectResponse
    public function download(BackupHistory $backup): BinaryFileResponse
    public function destroy(BackupHistory $backup): RedirectResponse
}
```

#### BackupService
```php
class BackupService
{
    public function createBackup(?int $userId = null): BackupHistory
    public function deleteBackup(BackupHistory $backup): bool
    public function cleanOldBackups(int $keepCount = 10): int
    public function getBackupPath(string $filename): string
}
```

#### BackupCommand (Scheduled)
```php
class BackupCommand extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Create database backup';
    
    public function handle(BackupService $backupService): int
}
```

### 7. User Management Components

#### UserController (Enhanced)
```php
class UserController extends Controller
{
    public function index(): View
    public function create(): View
    public function store(StoreUserRequest $request): RedirectResponse
    public function edit(User $user): View
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    public function destroy(User $user): RedirectResponse
    public function suspend(User $user): RedirectResponse
    public function activate(User $user): RedirectResponse
    public function activityHistory(User $user): View
}
```

#### User Model (Enhanced)
```php
class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;
    
    protected $fillable = ['name', 'email', 'password', 'is_suspended'];
    protected $casts = ['is_suspended' => 'boolean'];
    
    public function loginHistories(): HasMany
    public function activities(): HasMany
    public function isOnline(): bool
    public function lastLogin(): ?LoginHistory
}
```

### 8. PJU Management Components (Enhanced)

#### PjuPointController (Enhanced)
```php
class PjuPointController extends Controller
{
    public function index(Request $request): View
    public function create(): View
    public function store(StorePjuPointRequest $request): RedirectResponse
    public function edit(PjuPoint $pjuPoint): View
    public function update(UpdatePjuPointRequest $request, PjuPoint $pjuPoint): RedirectResponse
    public function destroy(PjuPoint $pjuPoint): RedirectResponse
    public function bulkDelete(BulkDeleteRequest $request): RedirectResponse
    public function bulkVerify(BulkVerifyRequest $request): RedirectResponse
    public function export(Request $request): BinaryFileResponse
    public function importForm(): View
    public function import(ImportPjuRequest $request): RedirectResponse
}
```

#### PjuPoint Model (Enhanced)
```php
class PjuPoint extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama', 'category_id', 'pju_type_id', 'latitude', 'longitude',
        'alamat', 'kecamatan_id', 'desa_id', 'status', 'type',
        'daya', 'is_verified', 'verified_by', 'verified_at', 'keterangan'
    ];
    
    public function category(): BelongsTo
    public function pjuType(): BelongsTo
    public function kecamatan(): BelongsTo
    public function desa(): BelongsTo
    public function verifier(): BelongsTo
    
    public function scopeVerified($query): Builder
    public function scopeUnverified($query): Builder
    public function scopeByCategory($query, int $categoryId): Builder
    public function scopeByStatus($query, string $status): Builder
    public function scopeByWilayah($query, ?int $kecamatanId, ?int $desaId): Builder
}
```

### 9. Dashboard Components

#### DashboardController (Enhanced)
```php
class DashboardController extends Controller
{
    public function index(): View
    public function superAdminDashboard(): View
    public function adminDishubDashboard(): View
    public function verifikatorDashboard(): View
}
```

## Data Models

### Enhanced Database Schema

#### users table (Enhanced)
```sql
- id: bigint (PK)
- name: varchar(255)
- email: varchar(255) UNIQUE
- password: varchar(255)
- is_suspended: boolean DEFAULT false
- email_verified_at: timestamp NULL
- remember_token: varchar(100) NULL
- created_at: timestamp
- updated_at: timestamp
```

#### categories table (Existing)
```sql
- id: bigint (PK)
- name: varchar(255) UNIQUE
- slug: varchar(255) UNIQUE
- icon: varchar(255) NULL
- is_active: boolean DEFAULT true
- created_at: timestamp
- updated_at: timestamp
```

#### pju_types table (Existing)
```sql
- id: bigint (PK)
- name: varchar(255) UNIQUE
- slug: varchar(255) UNIQUE
- is_active: boolean DEFAULT true
- created_at: timestamp
- updated_at: timestamp
```

#### kecamatans table (Existing)
```sql
- id: bigint (PK)
- name: varchar(255) UNIQUE
- created_at: timestamp
- updated_at: timestamp
```

#### desas table (Existing)
```sql
- id: bigint (PK)
- kecamatan_id: bigint (FK)
- name: varchar(255)
- created_at: timestamp
- updated_at: timestamp
- UNIQUE(kecamatan_id, name)
```

#### pju_points table (Enhanced)
```sql
- id: bigint (PK)
- nama: varchar(255)
- category_id: bigint (FK)
- pju_type_id: bigint (FK)
- latitude: decimal(10,8)
- longitude: decimal(11,8)
- alamat: text
- kecamatan_id: bigint NULL (FK)
- desa_id: bigint NULL (FK)
- status: enum('normal', 'rusak', 'mati')
- type: enum('input', 'verifikasi')
- daya: integer NULL (watts)
- is_verified: boolean DEFAULT false
- verified_by: bigint NULL (FK users)
- verified_at: timestamp NULL
- keterangan: text NULL
- created_at: timestamp
- updated_at: timestamp
```

#### system_settings table (Existing)
```sql
- id: bigint (PK)
- key: varchar(255) UNIQUE
- value: text
- type: enum('string', 'boolean', 'integer', 'json')
- group: varchar(50)
- description: text NULL
- created_at: timestamp
- updated_at: timestamp
```

#### login_histories table (Existing)
```sql
- id: bigint (PK)
- user_id: bigint NULL (FK)
- ip_address: varchar(45)
- user_agent: text
- status: enum('success', 'failed')
- login_at: timestamp
- logout_at: timestamp NULL
- created_at: timestamp
- updated_at: timestamp
```

#### backup_histories table (Existing)
```sql
- id: bigint (PK)
- filename: varchar(255)
- path: varchar(255)
- size: bigint (bytes)
- type: enum('manual', 'automatic', 'report')
- created_by: bigint NULL (FK users)
- created_at: timestamp
- updated_at: timestamp
```

#### activity_log table (Spatie Package)
```sql
- id: bigint (PK)
- log_name: varchar(255) NULL
- description: text
- subject_type: varchar(255) NULL
- subject_id: bigint NULL
- causer_type: varchar(255) NULL
- causer_id: bigint NULL
- properties: json NULL
- created_at: timestamp
- updated_at: timestamp
```

### Model Relationships

```
User
├── hasMany(LoginHistory)
├── hasMany(Activity) [morphMany]
├── hasMany(PjuPoint, 'verified_by')
└── hasMany(BackupHistory, 'created_by')

Category
└── hasMany(PjuPoint)

PjuType
└── hasMany(PjuPoint)

Kecamatan
├── hasMany(Desa)
└── hasMany(PjuPoint)

Desa
├── belongsTo(Kecamatan)
└── hasMany(PjuPoint)

PjuPoint
├── belongsTo(Category)
├── belongsTo(PjuType)
├── belongsTo(Kecamatan)
├── belongsTo(Desa)
├── belongsTo(User, 'verified_by')
└── morphMany(Activity)

LoginHistory
└── belongsTo(User)

BackupHistory
└── belongsTo(User, 'created_by')
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*


### Property Reflection

After analyzing all acceptance criteria, several patterns of redundancy emerged:

1. **Slug Generation Pattern (1.2, 1.3, 1.6, 1.7)**: Category and PJU Type both have identical slug generation behavior. These can be combined into a single property about slug generation for master data entities.

2. **Referential Integrity Pattern (1.4, 1.8)**: Category and PJU Type both prevent deletion when referenced. This is a single property about foreign key constraint enforcement.

3. **Filtering Pattern (3.3, 3.4, 3.5, 3.7, 3.8, 3.9)**: Multiple criteria test filtering by different attributes. These can be combined into properties about filter correctness.

4. **Automatic Logging Pattern (10.1-10.15)**: Many criteria test that actions are logged. These can be consolidated into properties about audit trail completeness.

5. **Dropdown Population (8.1, 8.2)**: Both test that dropdowns show only active items. Can be combined.

6. **Bulk Operations (8.7, 8.9)**: Both test bulk actions on selected items. Can be combined into a property about bulk operation correctness.

7. **Dashboard Counts (9.1, 9.4)**: Both test that dashboards display correct counts. Can be combined into a property about dashboard metric accuracy.

After reflection, we'll focus on unique, high-value properties that provide comprehensive validation coverage.

### Correctness Properties

**Property 1: Slug Generation Consistency**
*For any* master data entity (Category or PjuType) with a name, creating or updating that entity should generate a slug that is the lowercase, hyphenated version of the name.
**Validates: Requirements 1.2, 1.3, 1.6, 1.7**

**Property 2: Referential Integrity Protection**
*For any* master data entity (Category or PjuType) that is referenced by PJU points, attempting to delete that entity should fail and preserve the entity.
**Validates: Requirements 1.4, 1.8**

**Property 3: Cascade Deletion Completeness**
*For any* kecamatan with associated desas, deleting the kecamatan should remove all its desas from the database.
**Validates: Requirements 1.11**

**Property 4: Desa Filtering Correctness**
*For any* kecamatan filter applied to desa list, all returned desas should have kecamatan_id matching the filter value.
**Validates: Requirements 1.13**

**Property 5: Settings Persistence Round-Trip**
*For any* system setting with key and value, saving the setting then retrieving it by key should return the same value with correct type casting.
**Validates: Requirements 2.2, 2.4**

**Property 6: Coordinate Validation Bounds**
*For any* latitude value outside the range [-90, 90] or longitude value outside the range [-180, 180], the system should reject the map center coordinate update.
**Validates: Requirements 2.5**

**Property 7: Feature Toggle Visibility**
*For any* PJU form, when wilayah fields feature is enabled in settings, the form should display kecamatan and desa fields; when disabled, these fields should be hidden.
**Validates: Requirements 2.6, 8.3**

**Property 8: Activity Log Completeness**
*For any* data modification action (create, update, delete) on tracked models, the system should create an activity log entry with causer_id, subject_type, subject_id, and properties containing the changes.
**Validates: Requirements 2.9, 3.13, 3.14, 3.15, 10.1, 10.2, 10.3, 10.7, 10.8, 10.9, 10.10, 10.11, 10.12, 10.13, 10.14**

**Property 9: Login History Recording**
*For any* user authentication attempt (success or failure), the system should create a login_histories record with user_id, ip_address, user_agent, status, and login_at timestamp.
**Validates: Requirements 3.12, 10.4, 10.5**

**Property 10: Filter Result Correctness**
*For any* filter applied to login history or activity logs (by user, date range, status, or model type), all returned records should match the filter criteria.
**Validates: Requirements 3.3, 3.4, 3.5, 3.7, 3.8, 3.9, 10.15**

**Property 11: Response Time Calculation Accuracy**
*For any* set of PJU points with created_at and verified_at timestamps, the average response time should equal the sum of (verified_at - created_at) divided by the count of verified points.
**Validates: Requirements 3.10**

**Property 12: Export Data Consistency**
*For any* filtered dataset exported to Excel, the exported records should exactly match the records displayed in the filtered view.
**Validates: Requirements 3.11, 8.11**

**Property 13: Asset Health Score Calculation**
*For any* collection of PJU points, the Asset Health Score should equal (count of points with status='normal' / total count of points) * 100.
**Validates: Requirements 4.2**

**Property 14: Ranking Wilayah Sort Order**
*For any* ranking wilayah result set, areas should be sorted in descending order by count of PJU points with status='rusak' or status='mati'.
**Validates: Requirements 4.3**

**Property 15: Power Consumption Calculation**
*For any* collection of PJU points with status='normal', total power consumption should equal the sum of all daya (wattage) values for those points.
**Validates: Requirements 4.7**

**Property 16: Cost Estimation Formula**
*For any* total power consumption value and electricity rate, estimated cost should equal power consumption multiplied by the rate.
**Validates: Requirements 4.8**

**Property 17: Date Range Validation**
*For any* report period selection, if start date is after end date, the system should reject the selection with a validation error.
**Validates: Requirements 5.2**

**Property 18: Report Metadata Persistence**
*For any* generated report (PDF or Excel), the system should create a backup_histories record with filename, path, size, type, and created_by.
**Validates: Requirements 5.9**

**Property 19: Report File Deletion Completeness**
*For any* report deletion action, both the physical file in storage and the database record in backup_histories should be removed.
**Validates: Requirements 5.12, 6.6**

**Property 20: Backup Filename Format**
*For any* created backup file, the filename should match the pattern YYYY-MM-DD_HH-MM-SS_database.sql where the timestamp represents the creation time.
**Validates: Requirements 6.2**

**Property 21: Backup Metadata Accuracy**
*For any* created backup, the backup_histories record should contain accurate file size in bytes, creator user_id (or null for system), and creation timestamp.
**Validates: Requirements 6.3, 6.8**

**Property 22: Backup Cleanup Threshold**
*For any* backup storage exceeding the configured maximum count, the system should automatically delete the oldest backups until the count is at or below the limit.
**Validates: Requirements 6.9**

**Property 23: Online Status Determination**
*For any* user, the online status should be true if and only if the user's most recent login_histories record has login_at within the last 15 minutes and logout_at is null.
**Validates: Requirements 7.1, 7.2**

**Property 24: User Suspension Authentication Block**
*For any* user with is_suspended=true, authentication attempts should fail regardless of correct credentials.
**Validates: Requirements 7.4, 7.8**

**Property 25: User Activation Authentication Restore**
*For any* user with is_suspended=false, authentication attempts should succeed when credentials are correct.
**Validates: Requirements 7.5**

**Property 26: Active Master Data Dropdown Population**
*For any* PJU form (create or edit), category and type dropdowns should contain only entities where is_active=true.
**Validates: Requirements 8.1, 8.2**

**Property 27: Cascading Dropdown Filtering**
*For any* selected kecamatan in PJU form, the desa dropdown should contain only desas where kecamatan_id matches the selected kecamatan.
**Validates: Requirements 8.4**

**Property 28: Bulk Operation Completeness**
*For any* set of selected PJU points and bulk action (delete or verify), the action should be applied to all selected points and logged as a single activity.
**Validates: Requirements 8.7, 8.9**

**Property 29: Import Validation Completeness**
*For any* Excel file uploaded for import, if any row fails validation against database constraints, the system should reject the entire import and report all validation errors with row numbers.
**Validates: Requirements 8.13, 8.14**

**Property 30: Import Success Atomicity**
*For any* Excel file uploaded for import where all rows pass validation, the system should insert all records in a single transaction and create one activity log entry.
**Validates: Requirements 8.15**

**Property 31: Dashboard Metric Accuracy**
*For any* role-specific dashboard, displayed counts (total PJU, broken, verified, pending) should match the actual count of records meeting the criteria in the database.
**Validates: Requirements 9.1, 9.3, 9.4**

**Property 32: Verifikator Dashboard Filtering**
*For any* Verifikator dashboard PJU list, all displayed PJU points should have is_verified=false.
**Validates: Requirements 9.5**

**Property 33: Verification Action Completeness**
*For any* PJU point verified by a Verifikator, the system should set is_verified=true, verified_by to the verifier's user_id, verified_at to current timestamp, and create an activity log entry.
**Validates: Requirements 9.7**

## Error Handling

### Validation Errors

**Form Validation**:
- All form requests use Laravel Form Request classes with validation rules
- Validation errors return to form with old input and error messages
- Unique constraint violations display user-friendly messages
- Foreign key constraint violations display relationship information

**Import Validation**:
- Excel import validates all rows before inserting any data
- Validation errors include row number and field name
- Failed imports return detailed error report
- Successful imports provide summary of inserted records

### Database Errors

**Constraint Violations**:
- Foreign key violations caught and converted to user-friendly messages
- Unique constraint violations display which field must be unique
- Cascade delete failures prevented by checking relationships first

**Transaction Failures**:
- Bulk operations wrapped in database transactions
- Transaction rollback on any failure
- Error logged with full context for debugging

### File System Errors

**Backup Failures**:
- Database dump errors caught and logged
- Failed backups do not create backup_histories records
- Super Admin notified of backup failures via flash message

**Storage Errors**:
- File write failures caught and reported
- Disk space checks before large operations
- Failed file operations do not leave partial data

### Authentication Errors

**Login Failures**:
- Invalid credentials return generic error message (security)
- Suspended account attempts show specific suspension message
- Failed login attempts logged with reason

**Authorization Errors**:
- Unauthorized access attempts return 403 Forbidden
- Missing permissions display which permission is required
- Authorization failures logged for security audit

## Testing Strategy

### Dual Testing Approach

The testing strategy employs both unit tests and property-based tests to ensure comprehensive coverage:

**Unit Tests**: Focus on specific examples, edge cases, integration points, and error conditions. Unit tests verify concrete scenarios and are particularly useful for testing UI interactions, specific business rules, and integration between components.

**Property-Based Tests**: Verify universal properties across randomized inputs. Property tests run a minimum of 100 iterations per test to ensure properties hold across a wide range of inputs. Each property test references its corresponding design document property using the tag format: **Feature: luminous-jepara-enhancement, Property {number}: {property_text}**

### Property-Based Testing Configuration

**Framework**: Use **Pest PHP** with **pest-plugin-faker** for property-based testing in Laravel.

**Configuration**:
- Minimum 100 iterations per property test
- Each test tagged with feature name and property number
- Tests organized by feature area (master-data, analytics, backup, etc.)
- Generators created for all domain models (Category, PjuType, PjuPoint, etc.)

**Example Property Test Structure**:
```php
it('generates consistent slugs for master data entities', function () {
    // Property 1: Slug Generation Consistency
    // Feature: luminous-jepara-enhancement, Property 1
    
    $names = [
        'Test Category',
        'PJU LED Type',
        'Special-Characters!@#',
        'Multiple   Spaces',
    ];
    
    foreach ($names as $name) {
        $category = Category::create(['name' => $name]);
        expect($category->slug)->toBe(Str::slug($name));
        
        $category->update(['name' => $name . ' Updated']);
        expect($category->fresh()->slug)->toBe(Str::slug($name . ' Updated'));
    }
})->repeat(100);
```

### Unit Testing Focus Areas

**UI Interactions**:
- Form rendering with correct fields
- Button states (enabled/disabled)
- Tab navigation
- Chart rendering

**Edge Cases**:
- Empty datasets
- Boundary values (coordinates, dates)
- Special characters in input
- Large file uploads

**Integration Points**:
- Controller to service layer
- Service to model layer
- Model relationships
- External package integration (Spatie, DomPDF, Laravel Excel)

**Error Conditions**:
- Invalid input handling
- Database constraint violations
- File system errors
- Authentication failures

### Test Organization

```
tests/
├── Feature/
│   ├── MasterData/
│   │   ├── CategoryManagementTest.php
│   │   ├── PjuTypeManagementTest.php
│   │   ├── KecamatanManagementTest.php
│   │   └── DesaManagementTest.php
│   ├── SystemSettings/
│   │   └── SystemSettingsTest.php
│   ├── Monitoring/
│   │   ├── LoginHistoryTest.php
│   │   ├── ActivityLogsTest.php
│   │   └── ResponseTimeAnalyticsTest.php
│   ├── Analytics/
│   │   └── AnalyticsDashboardTest.php
│   ├── Reports/
│   │   └── ReportGenerationTest.php
│   ├── Backup/
│   │   └── BackupManagementTest.php
│   ├── UserManagement/
│   │   └── UserManagementTest.php
│   └── PjuManagement/
│       ├── PjuCrudTest.php
│       ├── BulkOperationsTest.php
│       └── ImportExportTest.php
├── Unit/
│   ├── Services/
│   │   ├── AnalyticsServiceTest.php
│   │   ├── ReportServiceTest.php
│   │   └── BackupServiceTest.php
│   └── Models/
│       ├── CategoryTest.php
│       ├── PjuTypeTest.php
│       ├── PjuPointTest.php
│       └── UserTest.php
└── Property/
    ├── MasterDataPropertiesTest.php
    ├── SettingsPropertiesTest.php
    ├── AuditLogPropertiesTest.php
    ├── AnalyticsPropertiesTest.php
    ├── BackupPropertiesTest.php
    ├── UserManagementPropertiesTest.php
    └── PjuManagementPropertiesTest.php
```

### Testing Best Practices

1. **Database Transactions**: All tests use database transactions that rollback after each test
2. **Factory Usage**: Use Laravel factories for creating test data
3. **Seeder Independence**: Tests do not depend on seeders; create required data in tests
4. **Isolation**: Each test is independent and can run in any order
5. **Descriptive Names**: Test names clearly describe what is being tested
6. **Arrange-Act-Assert**: Follow AAA pattern for test structure
7. **Mock External Services**: Mock external APIs and services
8. **Test Data Cleanup**: Ensure tests clean up any created files or resources
