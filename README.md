# Website Pemilihan Ketua ICP Periode 2026–2027

Sistem e-voting modern, transparan, dan realtime untuk **Pemilihan Ketua International Class Program (ICP) Periode 2026–2027**, dibangun dengan **Vanilla PHP 8.2 (Clean Routing)**, **MySQL 8.0**, **Chart.js**, dan dikemas penuh dalam **Docker** pada port **2345**.

---

## 🌟 Aturan Akses & Keamanan Sistem

1. **Akses Publik (Tanpa Login)**:
   - **Bilik Suara (Voting)**: `/voting` (dan `/` otomatis diarahkan langsung ke bilik suara).
   - **Login Panitia**: `/login` (Form login bersih hanya dengan placeholder).
   - Seluruh halaman lain **TIDAK DAPAT DIAKSES** oleh umum. Jika diakses, sistem otomatis mengalihkan ke halaman `/login`.
2. **Akses Panitia (Wajib Login)**:
   - **Dashboard Live Quick Count & Diagram**: `/dashboard` (diagram batang, donat, & per angkatan).
   - **Kelola Calon & Visi Misi**: `/admin/candidates`.
   - **Daftar Mahasiswa Pemilih**: `/admin/voters`.
   - **Pembersihan & Penghapusan Suara**:
     - *Hapus per suara*: Menghapus pemilih tertentu secara instan dan suara kandidat otomatis berkurang 1.
     - *Kosongkan Semua Suara*: Membersihkan seluruh riwayat pemilih dan mengembalikan suara semua calon ke 0.
   - **Unduh Laporan (PDF / XLSX)**: `/export/pdf` dan `/export/xlsx`.
3. **Kredensial Panitia**:
   - Username: `icp`
   - Password: `icp123`
   *(Kolom input tidak lagi diisi otomatis, melainkan murni berupa placeholder).*

---

## 🚀 Cara Menjalankan

### Menggunakan Skrip Kontrol:

#### Di Windows (CMD / PowerShell):
```cmd
.\start.bat
```
Untuk menghentikan:
```cmd
.\stop.bat
```

#### Di Linux / macOS / WSL / Git Bash:
```bash
chmod +x start.sh stop.sh
./start.sh
```
Untuk menghentikan:
```bash
./stop.sh
```

---

## 🌐 Daftar Rute URL

- **Bilik Suara (Publik)**: [http://localhost:2345/voting](http://localhost:2345/voting) (Akses utama pemilih)
- **Login Panitia**: [http://localhost:2345/login](http://localhost:2345/login)
- **Dashboard Quick Count (Khusus Panitia)**: [http://localhost:2345/dashboard](http://localhost:2345/dashboard)
- **Daftar & Hapus Pemilih**: [http://localhost:2345/admin/voters](http://localhost:2345/admin/voters)
- **Unduh PDF**: [http://localhost:2345/export/pdf](http://localhost:2345/export/pdf)
- **Unduh Excel**: [http://localhost:2345/export/xlsx](http://localhost:2345/export/xlsx)
