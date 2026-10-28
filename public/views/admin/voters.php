<?php
$pageTitle = 'Daftar Pemilih & Log Suara';
require __DIR__ . '/../layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="/admin/dashboard" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Dashboard Admin
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Daftar Mahasiswa Pemilih (Log Suara)</h1>
            <p class="text-xs text-slate-400 mt-1">Audit log partisipasi pemilihan ketua ICP periode 2026–2027.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <form action="/admin/voters/clear" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN? Semua daftar pemilih dan suara masuk akan dikosongkan/dibersihkan secara permanen!');" class="inline">
                <button type="submit" class="px-3.5 py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Kosongkan Semua Suara</span>
                </button>
            </form>
            <a href="/export/pdf" class="px-3.5 py-2 bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
            <a href="/export/xlsx" class="px-3.5 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export Excel</span>
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

    <!-- Filter & Search Bar -->
    <div class="glass-panel rounded-2xl p-4 border border-slate-800 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" id="voterSearchInput" onkeyup="filterVoterTable()" placeholder="Cari nama mahasiswa..."
                class="w-full pl-9 pr-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-xs focus:outline-none focus:border-blue-500">
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end text-xs text-slate-400">
            <span>Total Tercatat: <b class="text-white"><?= number_format($totalVotes, 0, ',', '.') ?></b> Mahasiswa</span>
        </div>
    </div>

    <!-- Table -->
    <div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table id="votersTable" class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4 text-center w-12">No</th>
                        <th class="px-5 py-4">NIM</th>
                        <th class="px-5 py-4">Nama Mahasiswa</th>
                        <th class="px-5 py-4">Kandidat Pilihan</th>
                        <th class="px-5 py-4">Waktu Voting</th>
                        <th class="px-5 py-4">IP Address</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    <?php if (empty($voters)): ?>
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-500 italic">
                                Belum ada suara masuk di sistem pemilihan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($voters as $v): ?>
                            <tr class="voter-row hover:bg-slate-800/40 transition">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-mono"><?= $no++ ?></td>
                                <td class="px-5 py-3.5 font-mono text-slate-300 font-bold text-xs"><?= htmlspecialchars($v['nim']) ?></td>
                                <td class="px-5 py-3.5 font-bold text-white voter-name"><?= htmlspecialchars($v['voter_name']) ?></td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-blue-400">
                                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 text-[10px] font-bold">
                                            #<?= sprintf('%02d', $v['candidate_number']) ?>
                                        </span>
                                        <span class="text-slate-200"><?= htmlspecialchars($v['candidate_name']) ?></span>
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-400"><?= $v['voted_at'] ?></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-500"><?= htmlspecialchars($v['ip_address'] ?? '-') ?></td>
                                <td class="px-5 py-3.5 text-right">
                                    <form action="/admin/voters/delete" method="POST" onsubmit="return confirm('Hapus suara atas nama <?= htmlspecialchars(addslashes($v['voter_name'])) ?> (NIM: <?= htmlspecialchars($v['nim']) ?>)? Suara kandidat akan otomatis berkurang 1.');" class="inline">
                                        <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 text-xs font-semibold transition" title="Hapus suara pemilih ini">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterVoterTable() {
    const input = document.getElementById('voterSearchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.voter-row');

    rows.forEach(row => {
        const nameCell = row.querySelector('.voter-name');
        if (nameCell) {
            const text = nameCell.textContent || nameCell.innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
