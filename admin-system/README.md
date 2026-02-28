# Admin System - Plug & Play

Sistem administrasi lengkap dengan role-based access control (RBAC) untuk Super Admin dan Badan Gizi.

## 📋 Fitur

### Super Admin
- ✅ Manajemen admin (CRUD)
- ✅ Manajemen permissions & roles
- ✅ Pengaturan global sistem
- ✅ Monitoring server health
- ✅ Activity logs
- ✅ Export data
- ✅ Laporan & pemantauan
- ✅ Password policy enforcement
- ✅ Login security (rate limiting, max attempts)
- ✅ Maintenance mode

### Badan Gizi (Admin Level 2)
- ✅ View semua data SPPG
- ✅ Kelola Admin SPPG
- ✅ Validasi & approval request
- ✅ Laporan masalah
- ✅ Grafik & statistik
- ✅ Export data
- ✅ Broadcast announcements
- ✅ Activity logs (filtered)

## 📁 Struktur Folder

```
admin-system/
├── backend/
│   ├── config/
│   │   └── bootstrap.js          # Security initialization
│   ├── lib/
│   │   └── permissions.js        # Permission logic
│   ├── middleware/
│   │   └── authorize.js          # Authorization middleware
│   ├── routes/
│   │   ├── admin.js              # Admin CRUD & auth
│   │   ├── permissions.js        # Permission management
│   │   └── settings.js           # System settings
│   ├── schema.sql                # Database schema
│   ├── integration-example.js    # Integration example
│   ├── .env.example              # Environment template
│   └── package.json              # Dependencies
├── frontend/
│   ├── pages/
│   │   ├── SuperAdminLogin.jsx   # Super Admin login
│   │   ├── SuperAdminProtectedRoute.jsx
│   │   ├── BadanGiziLogin.jsx    # Badan Gizi login
│   │   └── BadanGiziProtectedRoute.jsx
│   ├── lib/
│   │   ├── adminApi.js           # API client
│   │   ├── cookie.js             # Cookie management
│   │   ├── sessionTimeout.js     # Session timeout hook
│   │   └── export.js             # Export utilities (CSV/Excel/PDF)
│   └── README.md                 # Frontend documentation
└── README.md                      # This file
```

## 🚀 Instalasi

### 1. Backend Setup

```bash
# Install dependencies
npm install express cors mysql2 dotenv

# Setup database
mysql -u root -p < admin-system/backend/schema.sql

# Configure .env
cp .env.example .env
# Edit .env dengan database credentials
```

### 2. Frontend Setup

```bash
# Install dependencies
npm install react react-dom react-router-dom lucide-react

# Copy files ke project
cp -r admin-system/frontend/lib/* src/lib/
cp -r admin-system/frontend/pages/* src/pages/
```

**Catatan:** Dashboard components (SuperAdminDashboard, BadanGiziDashboard) tidak disertakan karena sangat spesifik ke aplikasi SPPG. Anda perlu membuat dashboard sendiri sesuai kebutuhan aplikasi Anda. Gunakan `adminApi.js` untuk mengambil data dari backend.

Lihat `frontend/README.md` untuk dokumentasi lengkap komponen dan contoh penggunaan.

### 3. Integrasi ke Express Server

```javascript
// server.js
import adminRoutes from './admin-system/backend/routes/admin.js';
import permissionsRoutes from './admin-system/backend/routes/permissions.js';
import settingsRoutes from './admin-system/backend/routes/settings.js';
import { bootstrapSecurity } from './admin-system/backend/config/bootstrap.js';

app.use('/api/admin', adminRoutes);
app.use('/api/permissions', permissionsRoutes);
app.use('/api/settings', settingsRoutes);

// Initialize security on startup
bootstrapSecurity().catch(console.error);
```

### 4. Integrasi ke React Router

```javascript
// App.jsx
import SuperAdminLogin from './pages/SuperAdminLogin';
import SuperAdminProtectedRoute from './pages/SuperAdminProtectedRoute';
import BadanGiziLogin from './pages/BadanGiziLogin';
import BadanGiziProtectedRoute from './pages/BadanGiziProtectedRoute';

// Import dashboard Anda sendiri
import YourSuperAdminDashboard from './pages/YourSuperAdminDashboard';
import YourBadanGiziDashboard from './pages/YourBadanGiziDashboard';

<Routes>
  <Route path="/superadmin" element={<SuperAdminLogin />} />
  <Route path="/superadmin/dashboard" element={
    <SuperAdminProtectedRoute>
      <YourSuperAdminDashboard />
    </SuperAdminProtectedRoute>
  } />
  <Route path="/badan-gizi" element={<BadanGiziLogin />} />
  <Route path="/badan-gizi/dashboard" element={
    <BadanGiziProtectedRoute>
      <YourBadanGiziDashboard />
    </BadanGiziProtectedRoute>
  } />
</Routes>
```

**Membuat Dashboard Anda:**
```javascript
// YourSuperAdminDashboard.jsx
import { useState, useEffect } from 'react';
import { adminApi } from '../lib/adminApi';
import { cookie } from '../lib/cookie';
import { useSessionTimeout } from '../lib/sessionTimeout';
import { exportAdmins } from '../lib/export';

export default function YourSuperAdminDashboard() {
  const [admin, setAdmin] = useState(null);
  const [admins, setAdmins] = useState([]);
  const [stats, setStats] = useState(null);

  // Auto-logout setelah inactivity
  useSessionTimeout({
    cookieName: 'super_admin',
    admin,
    timeoutMinutes: 60,
    onExpire: () => navigate('/superadmin')
  });

  useEffect(() => {
    const adminData = cookie.get('super_admin');
    setAdmin(adminData);
    
    // Fetch data
    adminApi.getAdmins().then(setAdmins);
    adminApi.getStats().then(setStats);
  }, []);

  return (
    <div>
      <h1>Welcome, {admin?.nama}</h1>
      {/* Build your dashboard UI here */}
      <button onClick={() => exportAdmins(admins, 'csv')}>
        Export to CSV
      </button>
    </div>
  );
}
```

## 🔐 Default Credentials

**Super Admin:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING:** Ubah password default setelah instalasi pertama!

## 📊 Database Tables

- `admins` - User accounts
- `roles` - Role definitions
- `permissions` - Permission definitions
- `role_permissions` - Role-permission mapping
- `admin_permissions` - Per-admin permission overrides
- `activity_logs` - Activity tracking
- `settings` - System configuration
- `laporan` - Reports/issues
- `request_data` - Data change requests

## 🎯 Permissions

### Super Admin (Full Access)
- admin.manage
- permissions.manage
- settings.manage
- logs.view
- laporan.view/create/update
- request.view/create/update
- sppg.view/manage
- export.data
- stats.view
- server.view

### Badan Gizi (Limited Access)
- laporan.view/create/update
- request.view/create/update
- sppg.view
- export.data
- stats.view
- logs.view

### Admin SPPG (Minimal Access)
- sppg.view/manage (own data only)

## 🔧 Konfigurasi

Edit melalui Super Admin Dashboard → Pengaturan Global:

- **Security:**
  - Maintenance mode
  - Max login attempts
  - Lock duration
  - Password policy
  - Rate limiting

- **Session:**
  - Session timeout (minutes)

- **Announcements:**
  - Global announcement
  - Super admin announcement
  - SPPG announcement

## 📝 API Endpoints

### Authentication
- `POST /api/admin/login` - Login
- `GET /api/admin/me` - Get current user

### Admin Management
- `GET /api/admin/list` - List all admins
- `POST /api/admin/register` - Create admin
- `PUT /api/admin/:id` - Update admin
- `DELETE /api/admin/:id` - Delete admin
- `PUT /api/admin/:id/password` - Change password

### Permissions
- `GET /api/permissions/catalog` - List all permissions
- `GET /api/permissions/roles` - List roles with permissions
- `PUT /api/permissions/roles/:code` - Update role permissions

### Settings
- `GET /api/settings` - Get all settings
- `PUT /api/settings` - Update settings
- `PUT /api/settings/announcement` - Update announcement
- `GET /api/settings/stats` - Get statistics
- `GET /api/settings/server-health` - Server health metrics

### Logs
- `GET /api/admin/logs` - Get activity logs

### Reports
- `GET /api/admin/laporan` - Get reports
- `POST /api/admin/laporan` - Create report
- `PUT /api/admin/laporan/:id/status` - Update report status

### Requests
- `GET /api/admin/request-data` - Get data requests
- `POST /api/admin/request-data` - Create request
- `PUT /api/admin/request-data/:id/status` - Update request status

## 🛡️ Security Features

1. **Password Hashing** - Custom hash algorithm
2. **Rate Limiting** - IP-based rate limiting
3. **Login Attempts** - Max attempts with auto-lock
4. **Session Management** - Configurable timeout
5. **Activity Logging** - All actions logged
6. **Permission Checking** - Middleware-based authorization
7. **Maintenance Mode** - Block non-super-admin access

## 🎨 Customization

### Dashboard
Dashboard components tidak disertakan karena sangat spesifik. Buat dashboard Anda sendiri menggunakan:
- `adminApi.js` untuk data fetching
- `cookie.js` untuk session management
- `sessionTimeout.js` untuk auto-logout
- `export.js` untuk export functionality

Contoh lengkap ada di section "Integrasi ke React Router" di atas.

### Branding
Ganti logo di login pages dengan logo aplikasi Anda.

### Colors
Edit Tailwind classes di login components sesuai brand colors Anda.

### Permissions
Add new permissions in `backend/config/bootstrap.js`:

```javascript
const PERMISSIONS = [
  { code: 'your.permission', label: 'Your Label', description: 'Description' },
  // ...
];
```

## 📦 Dependencies

### Backend
- express ^4.21.2
- cors ^2.8.5
- mysql2 ^3.12.0
- dotenv ^16.4.7

### Frontend
- react ^19.2.0
- react-dom ^19.2.0
- react-router-dom ^7.13.0
- lucide-react ^0.563.0

## 🐛 Troubleshooting

### Login gagal
- Cek database connection
- Cek password hash di database
- Cek activity_logs untuk error details

### Permission denied
- Cek role_permissions table
- Cek admin_permissions untuk overrides
- Verify admin role di admins table

### Session timeout
- Adjust `session_timeout_minutes` di settings
- Clear browser cookies

## 📄 License

MIT License - Free to use and modify

## 🤝 Support

Untuk pertanyaan atau issue, silakan buat issue di repository.

---

**Version:** 1.0.0  
**Last Updated:** 2024
