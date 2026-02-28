@echo off
TITLE Luminous Jepara - Server Launcher
echo ========================================================
echo   🌟 Mulai Menyiapkan Lingkungan Server Luminous Jepara 🌟
echo ========================================================
echo.

echo Memeriksa dependensi NPM...
if not exist "node_modules\" (
    echo Mengunduh paket NPM, ini mungkin butuh beberapa saat...
    call npm install
)

echo.
echo Menyalakan service Vite Frontend...
start "Vite Frontend Server" cmd /k "npm run dev"

echo.
echo Menyalakan service PHP Backend Laravel...
start "Laravel Backend Server" cmd /k "php artisan serve"

echo ========================================================
echo ✅ Semua server telah dijalankan di jendela terminal baru!
echo.
echo 🌍 Jika menggunakan fitur dari PHP artisan: 
echo Buka di browser URL berikut: http://localhost:8000
echo.
echo 📌 Jika menggunakan Laragon Auto Virtual Host:
echo Buka di browser URL berikut: http://luminous-jepara.test
echo.
echo Info Login:
echo Email: superadmin@luminousjepara.id
echo Password: password
echo ========================================================
pause
