<?php
$pageTitle = 'Dashboard Admin';
require __DIR__ . '/../layout/admin_header.php';

$candidates = $results['candidates'] ?? [];
$leader = !empty($candidates) ? $candidates[0] : null;
?>

<div class="space-y-8">
    <!-- Top Welcome Banner -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-slate-800 relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-900 to-blue-950/40">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-bold mb-3">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>PANEL KENDALI PANITIA RESMI</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                    Dashboard Pemilihan Ketua ICP <span class="text-blue-500">2026–2027</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                    Pusat komando dan pengawasan pemilihan. Pantau suara masuk secara realtime, kelola visi misi calon, validasi pemilih berdasar NIM unik, dan unduh berita acara resmi.
                </p>
            </div>

            <!-- Main CTA: Live Quick Count Screen -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <a href="/live-count" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs sm:text-sm font-bold shadow-xl shadow-emerald-600/30 transition flex items-center justify-center gap-2.5">
                    <i class="fa-solid fa-expand text-emerald-200"></i>
                    <span>Buka Layar Quick Count (Fullscreen)</span>
                </a>
                <a href="/voting" target="_blank" class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 text-xs sm:text-sm font-semibold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-person-booth text-blue-400"></i>
                    <span>Bilik Suara</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-lg">
            <i class="fa-solid fa-circle-check text-lg flex-shrink-0"></i>
            <span><?= htmlspecialchars($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-lg">
            <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
            <span><?= htmlspecialchars($flashError) ?></span>
        </div>
    <?php endif; ?>

    <!-- 4 Key Stat Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Stat 1: Total Suara -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Suara Masuk</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">
                    <i class="fa-solid fa-box-archive text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-black text-white"><?= number_format($totalVotes, 0, ',', '.') ?> <span class="text-xs text-slate-400 font-normal">suara</span></div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-400">
                <i class="fa-solid fa-circle-check"></i>
                <span>Tersimpan aman di MySQL</span>
            </div>
        </div>

        <!-- Stat 2: Kandidat Terdaftar -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kandidat Terdaftar</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>
            <div class="text-3xl font-black text-white"><?= $totalCandidates ?> <span class="text-xs text-slate-400 font-normal">paslon</span></div>
            <a href="/admin/candidates" class="mt-3 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                <span>Kelola Visi &amp; Misi</span>
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Stat 3: Validasi NIM -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Validasi Unik</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </div>
            </div>
            <div class="text-xl font-black text-amber-400">1 NIM = 1 Suara</div>
            <div class="mt-3 text-[11px] text-slate-400">
                Duplikasi otomatis ditolak
            </div>
        </div>

        <!-- Stat 4: Quick Count Live -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Layar Siaran</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i class="fa-solid fa-tv text-sm"></i>
                </div>
            </div>
            <div class="text-base font-bold text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Siap Proyektor</span>
            </div>
            <a href="/live-count" class="mt-3 inline-flex items-center gap-1 text-[11px] text-emerald-400 hover:text-emerald-300 font-semibold">
                <span>Buka Mode Fullscreen</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
            </a>
        </div>
    </div>

    <!-- Section: Candidate Votes Tally Overview & Recent Voters -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Candidates Vote Breakdown -->
        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-square-poll-vertical text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Ringkasan Perolehan Suara</h2>
                            <p class="text-xs text-slate-400">Akumulasi suara yang diperoleh masing-masing calon ketua</p>
                        </div>
                    </div>
                    <a href="/live-count" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                        <span>Layar Lengkap</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    <?php if (empty($candidates)): ?>
                        <div class="text-center py-8 text-slate-500 italic text-xs">Belum ada kandidat terdaftar.</div>
                    <?php else: ?>
                        <?php foreach ($candidates as $c): ?>
                            <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-600 text-white font-black text-xs">
                                            #<?= sprintf('%02d', $c['candidate_number']) ?>
                                        </span>
                                        <div>
                                            <div class="font-bold text-white text-sm"><?= htmlspecialchars($c['name']) ?></div>
                                            <?php if (!empty($c['tagline'])): ?>
                                                <div class="text-[11px] text-slate-400 italic line-clamp-1">"<?= htmlspecialchars($c['tagline']) ?>"</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-base font-black text-white"><?= number_format($c['votes_count'], 0, ',', '.') ?> <span class="text-xs text-slate-400 font-normal">suara</span></div>
                                        <div class="text-xs font-bold text-blue-400"><?= number_format($c['percentage'], 1) ?>%</div>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: <?= max(2, $c['percentage']) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <span>Total Pemilih Terverifikasi: <b class="text-white"><?= number_format($totalVotes, 0, ',', '.') ?></b></span>
                <a href="/admin/candidates" class="text-indigo-400 hover:text-indigo-300 font-semibold">+ Tambah / Kelola Calon</a>
            </div>
        </div>

        <!-- Col 3: Log Pemilih Terakhir (NIM) -->
        <div class="glass-panel rounded-2xl p-6 border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                        <h2 class="text-base font-bold text-white">Log Suara Terkini</h2>
                    </div>
                    <a href="/admin/voters" class="text-xs text-blue-400 hover:text-blue-300 font-semibold">Lihat Semua</a>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-80 pr-1">
                    <?php if (empty($recentVoters)): ?>
                        <div class="text-center py-12 text-slate-500 italic text-xs">Belum ada suara masuk.</div>
                    <?php else: ?>
                        <?php foreach ($recentVoters as $rv): ?>
                            <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-3 text-xs">
                                <div class="overflow-hidden">
                                    <div class="font-bold text-white truncate"><?= htmlspecialchars($rv['voter_name']) ?></div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">NIM: <span class="text-slate-300 font-semibold"><?= htmlspecialchars($rv['nim']) ?></span></div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                        #<?= sprintf('%02d', $rv['candidate_number']) ?>
                                    </span>
                                    <div class="text-[10px] text-slate-500 mt-1"><?= date('H:i:s', strtotime($rv['voted_at'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-800/80">
                <a href="/admin/voters" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold border border-slate-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-address-book text-xs text-indigo-400"></i>
                    <span>Buka Seluruh Data Pemilih</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Panels & Report Downloads -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Panel 1: Kelola Calon -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-1">Kelola Calon &amp; Visi Misi</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Tambah pasangan calon ketua, upload foto, perbarui nomor urut, visi, misi, dan program kerja unggulan.
                </p>
            </div>
            <div class="flex items-center gap-2 pt-3 border-t border-slate-800">
                <a href="/admin/candidates" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold text-center transition">
                    Daftar Calon
                </a>
                <a href="/admin/candidates/create" class="py-2 px-3 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-semibold border border-slate-700 transition">
                    + Baru
                </a>
            </div>
        </div>

        <!-- Panel 2: Unduh Laporan Resmi -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-400 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-file-arrow-down"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-1">Unduh Dokumen &amp; Laporan</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Ekspor berita acara pemilihan resmi dalam format PDF berstempel atau rekapan data spreadsheet Excel (XLSX).
                </p>
            </div>
            <div class="flex items-center gap-2 pt-3 border-t border-slate-800">
                <a href="/export/pdf" class="flex-1 py-2 px-3 bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold text-center transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <a href="/export/xlsx" class="flex-1 py-2 px-3 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 rounded-xl text-xs font-bold text-center transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Panel 3: Bilik Suara Mandiri -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-cyan-600/20 text-cyan-400 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-person-booth"></i>
                </div>
                <h3 class="text-sm font-bold text-white mb-1">Bilik Suara Publik (Voting)</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Halaman antarmuka yang diakses mahasiswa pemilih untuk memasukkan Nama dan NIM sebelum memberikan hak suaranya.
                </p>
            </div>
            <div class="pt-3 border-t border-slate-800">
                <a href="/voting" target="_blank" class="w-full py-2 px-3 bg-cyan-600/20 hover:bg-cyan-600/30 text-cyan-300 border border-cyan-500/30 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    <span>Buka Bilik Suara di Tab Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Danger Zone: Reset Data Pemilihan -->
    <div class="glass-panel rounded-2xl p-6 border border-red-500/30 bg-red-950/10">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-red-400 mb-1">Zona Bahaya: Reset Suara &amp; Kosongkan Pemilih</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Tindakan ini akan mengosongkan seluruh riwayat suara masuk dan mengembalikan perolehan suara semua kandidat menjadi 0. Data kandidat dan visi-misi tetap tersimpan.
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

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
