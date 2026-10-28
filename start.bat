@echo off
title Memulai Sistem Pemilihan Ketua ICP 2026-2027
echo ==========================================================
echo     MEMULAI SISTEM PEMILIHAN KETUA ICP 2026-2027
echo ==========================================================
echo Memeriksa Docker dan menjalankan container di port 2345...

if not exist downloads mkdir downloads
if not exist public\assets\uploads mkdir public\assets\uploads

docker compose up -d --build

echo.
echo Menunggu MySQL dan Web Server siap...
timeout /t 5 /nobreak >nul

echo.
echo ==========================================================
echo     SISTEM BERHASIL DIJALANKAN!
echo ==========================================================
echo  Silakan buka browser Anda di:
echo  -^> http://localhost:2345
echo.
echo  Info Login Panitia:
echo  -^> Username : icp
echo  -^> Password : icp123
echo.
echo  Folder Download Laporan (PDF/Excel):
echo  -^> .\downloads\
echo ==========================================================
pause
