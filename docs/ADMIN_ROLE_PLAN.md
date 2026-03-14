# Plan 3 Level Admin

## Tujuan
Menetapkan 3 level admin yang jelas, dengan fokus kontrol penuh di `super_admin` dan pembatasan akses berbasis permission untuk role lainnya.

## Level Admin
1. `super_admin`
2. `admin_dishub`
3. `verifikator`

## Matriks Fitur
| Fitur | super_admin | admin_dishub | verifikator |
|---|---|---|---|
| Login Admin Panel | Ya | Ya | Ya |
| Lihat Dashboard | Ya | Ya | Ya |
| Lihat Map & Data PJU | Ya | Ya | Ya |
| Tambah PJU | Ya | Ya | Tidak |
| Edit PJU | Ya | Ya | Tidak |
| Hapus PJU | Ya | Tidak | Tidak |
| Verifikasi PJU | Ya | Tidak | Ya |
| Kelola User Admin | Ya | Tidak | Tidak |
| Lihat Log Sistem | Ya | Tidak | Tidak |

## Mapping Permission
- `view_dashboard`: semua role admin
- `view_pju`: semua role admin
- `create_pju`: `super_admin`, `admin_dishub`
- `edit_pju`: `super_admin`, `admin_dishub`
- `delete_pju`: `super_admin`
- `verify_pju`: `super_admin`, `verifikator`
- `manage_users`: `super_admin`
- `view_logs`: `super_admin`

## Eksekusi Yang Sudah Dijalankan
1. Route admin diproteksi middleware `can:*` per fitur di `routes/web.php`.
2. User management diproteksi `can:manage_users`.
3. Fitur `Audit Log` dibuat untuk super admin (`/admin/logs`) dengan permission `view_logs`.
4. Seeder role-permission dibuat idempotent (aman dijalankan berulang).
5. Tombol/menu aksi utama di UI dibatasi berdasarkan permission (`@can`) pada halaman admin.
6. Test akses role untuk `Audit Log` ditambahkan (`tests/Feature/AdminLogAccessTest.php`).
7. Halaman khusus verifikator dibuat di `/admin/verifikasi` + test akses role (`tests/Feature/VerificationPageAccessTest.php`).

## Backlog Lanjutan (Opsional)
1. Buat halaman Audit Log (`view_logs`) khusus super admin.
2. Tambahkan dashboard widget berbeda per role (operasional vs verifikasi).
3. Tambahkan test feature untuk memastikan tiap role hanya bisa akses fitur yang sesuai.
