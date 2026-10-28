@echo off
title Menghentikan Sistem Pemilihan Ketua ICP 2026-2027
echo ==========================================================
echo     MENGHENTIKAN SISTEM PEMILIHAN KETUA ICP 2026-2027
echo ==========================================================

docker compose down

echo.
echo Container web dan database MySQL berhasil dihentikan.
echo Data pemilihan tetap tersimpan aman di Docker Volume.
echo ==========================================================
pause
