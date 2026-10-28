# 🗳️ Web Voting — Pemilihan Ketua ICP 2026–2027

Aplikasi e-voting berbasis **PHP 8.2 (native + routing)**, **MySQL 8.0**, dan **Docker**, dengan **live quick count realtime** (Server-Sent Events), diagram interaktif, panel admin bersidebar, serta export hasil ke **PDF** dan **Excel (XLSX)**.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## ✨ Fitur

| Fitur | Keterangan |
|---|---|
| **Bilik suara publik** | Pemilih mengisi **Nama + NIM** → lanjut memilih kandidat → e-receipt |
| **1 NIM = 1 suara** | NIM bersifat `UNIQUE` di database, divalidasi di frontend, backend, dan MySQL |
| **Live Quick Count** | Update realtime via SSE (+ polling fallback 3 detik), tombol **Full Screen** untuk proyektor |
| **Diagram interaktif** | Bar chart perolehan, doughnut chart persentase, line chart tren per jam, live feed pemilih |
| **Layout dinamis** | Jumlah kolom kartu kandidat menyesuaikan jumlah calon (1/2/3/4+), mobile-friendly |
| **Panel admin bersidebar** | Dashboard, kelola calon & visi-misi, data pemilih, export |
| **Manajemen pemilih** | Hapus suara per pemilih (tally otomatis berkurang) atau kosongkan semua suara |
| **Export** | PDF & XLSX, tersimpan juga di folder `./downloads/` |
| **Clean URL** | Tanpa `.php` di URL (Apache `mod_rewrite` + front controller) |
| **Offline-ready** | Tailwind, Chart.js, FontAwesome disajikan lokal → aman diakses via IP LAN tanpa internet |

---

## 🚀 Quick Start

**Prasyarat:** Docker Desktop / Docker Engine + Docker Compose sudah berjalan.

```bash
git clone git@github.com:nafiurohman/web-voting.git
cd web-voting

# Linux / macOS / Git Bash
./start.sh

# Windows (CMD / double-click)
start.bat
```

Buka **http://localhost:2345** (atau `http://<IP-SERVER>:2345` dari perangkat lain di jaringan yang sama).

Menghentikan:

```bash
./stop.sh      # atau stop.bat
```

> Data tetap tersimpan di Docker volume `icp_mysql_data`. Untuk reset total database: `docker compose down -v`.

---

## 🔐 Akun Default Panitia

| Username | Password |
|---|---|
| `icp` | `icp123` |

> ⚠️ **Segera ganti password** sebelum dipakai untuk pemilihan sungguhan (lihat [docs/DOKUMENTASI.md](docs/DOKUMENTASI.md#mengganti-password-admin)).

---

## 🧭 Halaman & Hak Akses

| URL | Akses | Fungsi |
|---|---|---|
| `/` | Publik | Redirect ke `/voting` |
| `/voting` | Publik | Bilik suara |
| `/login` | Publik | Login panitia |
| `/admin/dashboard` | Admin | Dashboard admin |
| `/live-count` | Admin | Layar quick count realtime + fullscreen |
| `/admin/candidates` | Admin | Kelola calon & visi-misi |
| `/admin/voters` | Admin | Data pemilih, hapus / kosongkan suara |
| `/export/pdf`, `/export/xlsx` | Admin | Unduh laporan |

Semua halaman non-publik otomatis diarahkan ke `/login` bila belum login.

---

## 📁 Struktur Proyek

```
.
├── Dockerfile               # PHP 8.2 + Apache (mod_rewrite, mod_headers)
├── docker-compose.yml       # Service web (port 2345) + MySQL 8.0
├── mysql-init/01-init.sql   # Skema & seed data
├── public/                  # Document root
│   ├── index.php            # Front controller / router
│   ├── .htaccess            # Clean URL + CORS
│   ├── assets/              # CSS, JS, font lokal, upload foto
│   └── views/               # Template halaman (publik & admin)
├── src/
│   ├── Config/Database.php
│   ├── Controllers/         # Auth, Voting, Dashboard, Admin, Export
│   ├── Models/              # User, Candidate, Vote
│   └── Utils/               # FPDF, PdfReport, SimpleXLSXWriter
├── downloads/               # Hasil export PDF/XLSX
├── docs/DOKUMENTASI.md      # Dokumentasi teknis lengkap
├── start.sh / start.bat
└── stop.sh  / stop.bat
```

---

## 📚 Dokumentasi Lengkap

Arsitektur, alur voting, skema database, API, konfigurasi, dan troubleshooting:
➡️ **[docs/DOKUMENTASI.md](docs/DOKUMENTASI.md)**

---

## 📄 Lisensi

Dirilis di bawah **MIT License** — lihat [LICENSE](LICENSE).
