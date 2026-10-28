<?php
$pageTitle = 'Panel Admin Panitia';
require __DIR__ . '/../layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold">
                    Administrator Mode
                </span>
                <span class="text-xs text-slate-500">•</span>
                <span class="text-xs text-slate-400">User: <b>icp</b></span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Panel Kontrol Panitia Pemilihan ICP</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola data calon ketua, visi misi, pantau pemilih, serta export laporan resmi.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="/admin/candidates/create" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-blue-600/30">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Tambah Calon Baru</span>
            </a>
            <a href="/dashboard" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white rounded-xl text-xs font-semibold border border-slate-700 transition flex items-center gap-1.5">
                <i class="fa-solid fa-chart-line text-emerald-400"></i>
                <span>Lihat Quick Count</span>
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span><?= htmlspecialchars($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-semibold flex items-center gap-2.5">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span><?= htmlspecialchars($flashError) ?></span>
        </div>
    <?php endif; ?>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass-panel rounded-2xl p-5 border border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kandidat Terdaftar</span>
                <i class="fa-solid fa-users text-blue-400 text-base"></i>
            </div>
            <div class="text-3xl font-black text-white"><?= $totalCandidates ?> <span class="text-sm font-normal text-slate-400">Pasangan</span></div>
            <a href="/admin/candidates" class="mt-3 inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300 font-semibold">
                <span>Kelola Calon &amp; Visi Misi</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Suara Masuk</span>
                <i class="fa-solid fa-check-to-slot text-emerald-400 text-base"></i>
            </div>
            <div class="text-3xl font-black text-emerald-400"><?= number_format($totalVotes, 0, ',', '.') ?> <span class="text-sm font-normal text-slate-400">Suara</span></div>
            <a href="/admin/voters" class="mt-3 inline-flex items-center gap-1 text-xs text-emerald-400 hover:text-emerald-300 font-semibold">
                <span>Lihat Daftar Mahasiswa Pemilih</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Hasil</span>
                <i class="fa-solid fa-download text-amber-400 text-base"></i>
            </div>
            <div class="text-sm text-slate-300 mb-3">Tersedia format PDF resmi &amp; Excel (XLSX) tersimpan di folder download.</div>
            <div class="flex gap-2">
                <a href="/export/pdf" class="px-2.5 py-1.5 rounded-lg bg-rose-600/20 text-rose-300 border border-rose-500/30 text-xs font-bold hover:bg-rose-600/30 transition">
                    <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="/export/xlsx" class="px-2.5 py-1.5 rounded-lg bg-emerald-600/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold hover:bg-emerald-600/30 transition">
                    <i class="fa-solid fa-file-excel mr-1"></i> Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <!-- Card 1: Kelola Calon -->
        <div class="glass-panel rounded-2xl p-6 border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-lg mb-4">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Kelola Calon Ketua &amp; Visi Misi</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Tambah pasangan calon ketua baru, ubah nama, unggah foto resmi, serta perbarui visi, misi, dan program kerja unggulan yang akan langsung tampil di halaman bilik suara voting pemilih.
                </p>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                <a href="/admin/candidates" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition">
                    Buka Daftar Calon
                </a>
                <a href="/admin/candidates/create" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition">
                    + Tambah Calon
                </a>
            </div>
        </div>

        <!-- Card 2: Data Pemilih & Log -->
        <div class="glass-panel rounded-2xl p-6 border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-lg mb-4">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Daftar Mahasiswa Pemilih (Audit Log)</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Pantau daftar lengkap mahasiswa yang telah memberikan suara, validasi NIM unik, timestamp waktu memilih, dan kandidat yang dipilih untuk keperluan transparansi dan akuntabilitas pemilihan.
                </p>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                <a href="/admin/voters" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition">
                    Lihat Data Pemilih
                </a>
                <a href="/export/xlsx" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition">
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Danger Zone: Reset Pemilihan -->
    <div class="glass-panel rounded-2xl p-6 border border-red-500/30 bg-red-950/10">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-red-400 mb-1">Zona Bahaya: Reset Data Pemilihan</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Tindakan ini akan mengosongkan seluruh riwayat suara masuk dan mengembalikan perolehan suara semua kandidat menjadi 0. Gunakan fitur ini jika ingin memulai simulasi baru atau memulai hari pemungutan suara resmi.
                </p>

                <form action="/admin/reset" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN? Seluruh riwayat data pemilih dan perolehan suara akan langsung dikosongkan/dibersihkan secara permanen!');">
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-red-600/30">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Kosongkan &amp; Bersihkan Semua Suara</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
