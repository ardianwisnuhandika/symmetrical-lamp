# Luminous Jepara - Login Information

## Akun Login yang Tersedia

### 1. Super Admin (Full Access)
- **Email:** `superadmin@luminousjepara.id`
- **Password:** `password`
- **Akses:** 
  - Kelola semua user
  - Kelola semua data PJU
  - Verifikasi data
  - Lihat semua log

### 2. Admin Dishub
- **Email:** `admin@luminousjepara.id`
- **Password:** `password`
- **Akses:**
  - Lihat dashboard
  - Tambah/edit data PJU
  - Lihat data PJU

### 3. Verifikator
- **Email:** `verifikator@luminousjepara.id`
- **Password:** `password`
- **Akses:**
  - Lihat dashboard
  - Lihat data PJU
  - Verifikasi data PJU

---

## Cara Menjalankan Aplikasi

### Opsi 1: Menggunakan Laragon (Recommended)
1. Buka aplikasi **Laragon**
2. Klik tombol **"Start All"**
3. Akses via browser: **http://luminous-jepara.test**

### Opsi 2: Manual via Terminal
1. Jalankan command:
   ```cmd
   php artisan serve
   ```
2. Akses via browser: **http://localhost:8000** atau **http://127.0.0.1:8000**

---

## URL Penting

- **Landing Page:** `/`
- **Login:** `/login`
- **Dashboard Admin:** `/admin/dashboard`
- **Peta Monitoring (Public):** `/monitoring-map`
- **Kelola PJU:** `/admin/pju`
- **Kelola User:** `/admin/users` (Super Admin only)

---

## Troubleshooting

Jika login gagal, pastikan:
1. Database sudah di-migrate dan di-seed
2. Server sudah berjalan
3. Gunakan email dan password yang benar (case-sensitive)

Untuk reset database dan buat ulang user:
```cmd
php artisan migrate:fresh --seed --force
```
