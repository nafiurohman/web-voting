<?php
$pageTitle = 'Beranda Pemilihan';
require __DIR__ . '/layout/header.php';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden py-16 md:py-24 border-b border-slate-800/60">
    <!-- Glow Background Gradients -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Live Status Pill -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/80 mb-6 shadow-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold text-slate-300">Pemungutan Suara Sedang Berlangsung</span>
            <span class="text-slate-600">•</span>
            <span class="text-xs font-bold text-blue-400"><?= number_format($totalVotes, 0, ',', '.') ?> Suara Telah Masuk</span>
        </div>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight max-w-4xl mx-auto leading-tight">
            Pemilihan Ketua <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-400 bg-clip-text text-transparent">ICP</span>
            <br>Periode <span class="text-blue-500 underline decoration-blue-500/30 decoration-wavy">2026–2027</span>
        </h1>

        <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Gunakan hak suara Anda secara demokratis, transparan, dan realtime. Tentukan pemimpin terbaik yang akan membawa International Class Program semakin unggul dan mendunia.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/voting" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold shadow-xl shadow-blue-600/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition flex items-center justify-center gap-2 group">
                <i class="fa-solid fa-vote-yea text-lg group-hover:rotate-12 transition-transform"></i>
                <span>Masuk Bilik Suara (Voting)</span>
            </a>
            <a href="/dashboard" class="w-full sm:w-auto px-8 py-3.5 bg-slate-800/90 hover:bg-slate-700/90 text-slate-200 hover:text-white rounded-xl font-semibold border border-slate-700 hover:border-slate-600 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-chart-pie text-emerald-400"></i>
                <span>Lihat Quick Count Realtime</span>
            </a>
        </div>

        <!-- Quick Summary Bar -->
        <div class="mt-12 max-w-3xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-4 text-left">
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                <div class="text-xs text-slate-400 mb-1">Total Calon</div>
                <div class="text-2xl font-bold text-white"><?= count($candidates) ?> Pasangan</div>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                <div class="text-xs text-slate-400 mb-1">Suara Masuk</div>
                <div class="text-2xl font-bold text-blue-400"><?= number_format($totalVotes, 0, ',', '.') ?></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                <div class="text-xs text-slate-400 mb-1">Metode Voting</div>
                <div class="text-2xl font-bold text-emerald-400">Realtime E-Vote</div>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                <div class="text-xs text-slate-400 mb-1">Keamanan Data</div>
                <div class="text-2xl font-bold text-indigo-400">1 Mhs 1 Suara</div>
            </div>
        </div>
    </div>
</section>

<!-- Flow / Cara Memilih -->
<section class="py-12 bg-slate-900/40 border-b border-slate-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-2">Tata Cara Pemilihan</h2>
            <p class="text-2xl font-bold text-white">3 Langkah Mudah Memberikan Hak Suara</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl glass-panel relative">
                <div class="w-12 h-12 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center font-extrabold text-lg mb-4 border border-blue-500/30">
                    1
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Input Identitas Pemilih</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Masukkan Nama Lengkap Anda beserta Angkatan (contoh: 2023, 2024). Sistem akan memvalidasi agar setiap mahasiswa hanya dapat memilih satu kali secara sah.
                </p>
            </div>

            <div class="p-6 rounded-2xl glass-panel relative">
                <div class="w-12 h-12 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center font-extrabold text-lg mb-4 border border-emerald-500/30">
                    2
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Pelajari Visi &amp; Misi Calon</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Periksa profil lengkap kandidat ketua, program kerja unggulan, serta visi &amp; misi yang telah diinput oleh panitia resmi pemilihan.
                </p>
            </div>

            <div class="p-6 rounded-2xl glass-panel relative">
                <div class="w-12 h-12 rounded-xl bg-amber-600/20 text-amber-400 flex items-center justify-center font-extrabold text-lg mb-4 border border-amber-500/30">
                    3
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Pilih &amp; Pantau Quick Count</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Klik kandidat pilihan Anda, lakukan konfirmasi, dan suara Anda seketika itu juga akan memperbarui grafik di Dashboard Quick Count secara realtime!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Candidate Showcase -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Daftar Kandidat</h2>
                <p class="text-3xl font-extrabold text-white">Calon Ketua ICP Periode 2026–2027</p>
            </div>
            <a href="/voting" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-400 hover:text-blue-300">
                <span>Ke Halaman Voting</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($candidates as $c): ?>
                <div class="rounded-2xl glass-panel overflow-hidden border border-slate-800 hover:border-blue-500/50 transition-all duration-300 flex flex-col group">
                    <!-- Photo with candidate number badge -->
                    <div class="relative h-60 bg-slate-900 overflow-hidden flex items-center justify-center p-4">
                        <img src="<?= htmlspecialchars($c['photo']) ?>" alt="<?= htmlspecialchars($c['name']) ?>" class="h-full w-full object-contain group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-4 left-4 px-3 py-1 rounded-lg bg-blue-600/90 text-white font-black text-sm tracking-wide shadow-md">
                            NO. #<?= sprintf('%02d', $c['candidate_number']) ?>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">
                            <?= htmlspecialchars($c['name']) ?>
                        </h3>
                        <?php if (!empty($c['tagline'])): ?>
                            <p class="text-xs font-semibold text-blue-400 mt-1 italic">
                                "<?= htmlspecialchars($c['tagline']) ?>"
                            </p>
                        <?php endif; ?>

                        <div class="mt-4 pt-4 border-t border-slate-800/80 flex-1">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Visi Utama:</div>
                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                                <?= nl2br(htmlspecialchars($c['vision'])) ?>
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between gap-3">
                            <a href="/voting" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-center rounded-xl font-semibold text-sm transition shadow-md shadow-blue-600/20">
                                Pilih Kandidat Ini
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
