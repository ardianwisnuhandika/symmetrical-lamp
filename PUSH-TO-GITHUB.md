# Cara Push ke GitHub

## Langkah 1: Buat Repository Baru di GitHub

1. Buka https://github.com/new
2. Isi form:
   - **Repository name:** `luminous-jepara` (atau nama lain yang Anda inginkan)
   - **Description:** Sistem Monitoring PJU (Penerangan Jalan Umum) Kabupaten Jepara
   - **Visibility:** Public atau Private (pilih sesuai kebutuhan)
   - **JANGAN centang** "Initialize this repository with a README"
3. Klik **"Create repository"**

## Langkah 2: Push ke GitHub

Setelah repository dibuat, GitHub akan menampilkan instruksi. Jalankan command berikut di terminal:

```cmd
git remote add origin https://github.com/ardianwisnuhandika/luminous-jepara.git
git branch -M main
git push -u origin main
```

**ATAU** jika nama repository berbeda, ganti URL-nya:

```cmd
git remote add origin https://github.com/ardianwisnuhandika/NAMA-REPO-ANDA.git
git branch -M main
git push -u origin main
```

## Langkah 3: Masukkan Credentials

Saat diminta username dan password:
- **Username:** ardianwisnuhandika
- **Password:** Gunakan **Personal Access Token** (bukan password GitHub biasa)

### Cara Membuat Personal Access Token:
1. Buka https://github.com/settings/tokens
2. Klik **"Generate new token"** → **"Generate new token (classic)"**
3. Beri nama: `Luminous Jepara Push`
4. Centang scope: **repo** (full control of private repositories)
5. Klik **"Generate token"**
6. **COPY token** yang muncul (hanya muncul sekali!)
7. Gunakan token ini sebagai password saat push

## Status Saat Ini

✅ Git repository sudah di-initialize
✅ Semua file sudah di-commit
✅ Siap untuk di-push ke GitHub

## Troubleshooting

### Jika error "remote origin already exists":
```cmd
git remote remove origin
git remote add origin https://github.com/ardianwisnuhandika/luminous-jepara.git
```

### Jika ingin ganti nama branch dari master ke main:
```cmd
git branch -M main
```

### Jika ingin cek status:
```cmd
git status
git log --oneline
```
