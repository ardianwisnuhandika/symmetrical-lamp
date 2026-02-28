# Admin System - Frontend

Frontend components untuk sistem administrasi berbasis role (RBAC) dengan React.

## Struktur Folder

```
frontend/
├── lib/                    # Utility libraries
│   ├── adminApi.js        # API client untuk backend admin
│   ├── cookie.js          # Cookie management utility
│   ├── sessionTimeout.js  # Session timeout hook
│   └── export.js          # Data export utilities (CSV, Excel, PDF)
├── pages/                 # React components
│   ├── SuperAdminLogin.jsx
│   ├── SuperAdminProtectedRoute.jsx
│   ├── BadanGiziLogin.jsx
│   └── BadanGiziProtectedRoute.jsx
└── README.md
```

## Komponen Utama

### 1. Login Components

#### SuperAdminLogin.jsx
- Login page untuk Super Admin
- Captcha verification (simple math puzzle)
- Maintenance mode detection
- System announcements display

#### BadanGiziLogin.jsx
- Login page untuk Badan Gizi role
- Captcha verification
- Maintenance mode blocking
- System announcements display

### 2. Protected Route Components

#### SuperAdminProtectedRoute.jsx
```jsx
import SuperAdminProtectedRoute from './pages/SuperAdminProtectedRoute';

<SuperAdminProtectedRoute>
  <YourProtectedComponent />
</SuperAdminProtectedRoute>
```

#### BadanGiziProtectedRoute.jsx
```jsx
import BadanGiziProtectedRoute from './pages/BadanGiziProtectedRoute';

<BadanGiziProtectedRoute>
  <YourProtectedComponent />
</BadanGiziProtectedRoute>
```

### 3. Utility Libraries

#### adminApi.js
API client untuk komunikasi dengan backend:
- `login(username, password)` - Login admin
- `getAdmins()` - Get all admins
- `register(data)` - Register new admin
- `updateAdmin(id, data)` - Update admin
- `deleteAdmin(id)` - Delete admin
- `getStats()` - Get system statistics
- `getLogs(limit)` - Get activity logs
- `getSettings()` - Get system settings
- `updateSettings(data)` - Update settings
- Dan lainnya...

#### cookie.js
Cookie management:
```javascript
import { cookie } from './lib/cookie';

// Set cookie (expires in days)
cookie.set('name', value, 7);

// Set cookie (expires in minutes)
cookie.setWithMinutes('name', value, 60);

// Get cookie
const data = cookie.get('name');

// Remove cookie
cookie.remove('name');
```

#### sessionTimeout.js
React hook untuk auto-logout setelah inactivity:
```javascript
import { useSessionTimeout } from './lib/sessionTimeout';

useSessionTimeout({
  cookieName: 'super_admin',
  admin: adminData,
  timeoutMinutes: 60,
  onExpire: () => navigate('/login')
});
```

#### export.js
Export data ke berbagai format:
```javascript
import { exportAdmins, exportToCSV, exportToExcel, exportToPDF } from './lib/export';

// Export admin list
exportAdmins(admins, 'csv');
exportAdmins(admins, 'excel');
exportAdmins(admins, 'pdf');

// Custom export
const columns = [
  { key: 'name', label: 'Nama' },
  { key: 'email', label: 'Email' }
];
exportToCSV(data, 'filename', columns);
```

## Integrasi dengan React Router

```jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import SuperAdminLogin from './pages/SuperAdminLogin';
import SuperAdminProtectedRoute from './pages/SuperAdminProtectedRoute';
import BadanGiziLogin from './pages/BadanGiziLogin';
import BadanGiziProtectedRoute from './pages/BadanGiziProtectedRoute';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Super Admin Routes */}
        <Route path="/superadmin" element={<SuperAdminLogin />} />
        <Route 
          path="/superadmin/dashboard" 
          element={
            <SuperAdminProtectedRoute>
              <SuperAdminDashboard />
            </SuperAdminProtectedRoute>
          } 
        />

        {/* Badan Gizi Routes */}
        <Route path="/badan-gizi" element={<BadanGiziLogin />} />
        <Route 
          path="/badan-gizi/dashboard" 
          element={
            <BadanGiziProtectedRoute>
              <BadanGiziDashboard />
            </BadanGiziProtectedRoute>
          } 
        />
      </Routes>
    </BrowserRouter>
  );
}
```

## Dependencies

Pastikan package.json Anda memiliki dependencies berikut:

```json
{
  "dependencies": {
    "react": "^18.0.0",
    "react-dom": "^18.0.0",
    "react-router-dom": "^6.0.0",
    "lucide-react": "^0.263.0"
  }
}
```

## Styling

Komponen menggunakan Tailwind CSS. Pastikan Tailwind sudah dikonfigurasi di project Anda.

## Environment Variables

Buat file `.env` di root project:

```env
VITE_API_URL=http://localhost:3000/api
```

Update `adminApi.js` untuk menggunakan environment variable:

```javascript
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';
```

## Catatan Penting

1. **Session Management**: Session disimpan di cookie dan localStorage. Cookie menyimpan data admin, localStorage menyimpan timestamp aktivitas terakhir.

2. **Security**: 
   - Password tidak pernah disimpan di frontend
   - Session timeout otomatis setelah inactivity
   - Protected routes memvalidasi role sebelum render

3. **Maintenance Mode**: Ketika maintenance mode aktif, hanya Super Admin yang bisa login. Badan Gizi dan Admin SPPG akan diblokir.

4. **Captcha**: Simple math puzzle untuk mencegah bot. Bisa diganti dengan reCAPTCHA atau solusi lain.

## Customization

### Mengubah Session Timeout Default
Edit di `sessionTimeout.js`:
```javascript
const getTimeoutMinutes = (value) => {
  const parsed = Number(value);
  if (!Number.isFinite(parsed) || parsed <= 0) return 60; // Ubah default di sini
  return parsed;
};
```

### Menambah Export Format Baru
Tambahkan function baru di `export.js`:
```javascript
export const exportToJSON = (data, filename) => {
  const json = JSON.stringify(data, null, 2);
  const blob = new Blob([json], { type: 'application/json' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `${filename}.json`;
  link.click();
};
```

## Troubleshooting

### Cookie tidak tersimpan
- Pastikan `SameSite=Lax` sesuai dengan konfigurasi server
- Untuk development dengan domain berbeda, gunakan `SameSite=None; Secure`

### Session timeout tidak bekerja
- Cek localStorage tidak diblokir oleh browser
- Pastikan event listeners terdaftar dengan benar

### Protected route redirect loop
- Pastikan cookie name konsisten
- Cek role validation logic

## License

Lihat LICENSE file di root project.
