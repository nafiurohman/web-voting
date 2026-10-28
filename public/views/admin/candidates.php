<?php
$pageTitle = 'Kelola Calon Ketua';
require __DIR__ . '/../layout/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="/admin/dashboard" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Dashboard Admin
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Kelola Calon Ketua &amp; Visi Misi</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar kandidat yang tampil di bilik suara pemilih.</p>
        </div>

        <a href="/admin/candidates/create" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-md shadow-blue-600/30">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Calon Ketua Baru</span>
        </a>
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

    <!-- Candidates Table -->
    <div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4 text-center">No Urut</th>
                        <th class="px-5 py-4">Foto</th>
                        <th class="px-5 py-4">Nama Pasangan Calon</th>
                        <th class="px-5 py-4">Visi &amp; Misi Singkat</th>
                        <th class="px-5 py-4 text-center">Suara</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    <?php if (empty($candidates)): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-500 italic">
                                Belum ada kandidat terdaftar. Silakan klik tombol "Tambah Calon Ketua Baru".
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($candidates as $c): ?>
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg bg-blue-600/20 text-blue-400 border border-blue-500/30 font-black text-sm">
                                        #<?= sprintf('%02d', $c['candidate_number']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <img src="<?= htmlspecialchars($c['photo']) ?>" alt="<?= htmlspecialchars($c['name']) ?>" class="w-12 h-12 rounded-xl object-cover bg-slate-800 border border-slate-700">
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-white text-sm"><?= htmlspecialchars($c['name']) ?></div>
                                    <?php if (!empty($c['tagline'])): ?>
                                        <div class="text-[11px] text-blue-400 italic line-clamp-1">"<?= htmlspecialchars($c['tagline']) ?>"</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <div class="text-slate-300 line-clamp-2 leading-relaxed">
                                        <b>Visi:</b> <?= htmlspecialchars($c['vision']) ?>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="font-bold text-white text-sm"><?= number_format($c['votes_count'], 0, ',', '.') ?></span>
                                    <span class="text-[10px] text-slate-500 block">suara</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/admin/candidates/edit?id=<?= $c['id'] ?>" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 font-semibold transition flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                            <span>Edit</span>
                                        </a>
                                        <form action="/admin/candidates/delete" method="POST" onsubmit="return confirm('Yakin ingin menghapus Kandidat #<?= $c['candidate_number'] ?> (<?= htmlspecialchars($c['name']) ?>)? Data suara terkait akan ikut terhapus!');" class="inline">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 font-semibold transition flex items-center gap-1" title="Hapus Calon">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
