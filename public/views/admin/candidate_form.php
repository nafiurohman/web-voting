<?php
$pageTitle = $isEdit ? 'Edit Calon Ketua' : 'Tambah Calon Ketua Baru';
require __DIR__ . '/../layout/admin_header.php';
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-8 pb-4 border-b border-slate-800">
        <a href="/admin/candidates" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1.5 mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali ke Daftar Calon</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
            <?= $isEdit ? 'Edit Data Calon Ketua #' . sprintf('%02d', $candidate['candidate_number']) : 'Tambah Calon Ketua Baru' ?>
        </h1>
        <p class="text-xs text-slate-400 mt-1">Data dan visi misi yang Anda simpan akan langsung ditampilkan di halaman bilik suara pemilih.</p>
    </div>

    <!-- Form Card -->
    <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-2xl">
        <form action="<?= $isEdit ? '/admin/candidates/edit' : '/admin/candidates/create' ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $candidate['id'] ?>">
                <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($candidate['photo']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Nomor Urut -->
                <div>
                    <label for="candidate_number" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nomor Urut <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="candidate_number" name="candidate_number" required min="1" max="99"
                        value="<?= htmlspecialchars((string)($candidate['candidate_number'] ?? $nextNumber)) ?>"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-blue-500 text-sm">
                </div>

                <!-- Nama Calon -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nama Pasangan Calon Ketua &amp; Wakil <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Adrian Wijaya & Sarah Amanda"
                        value="<?= htmlspecialchars($candidate['name'] ?? '') ?>"
                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm">
                </div>
            </div>

            <!-- Tagline -->
            <div>
                <label for="tagline" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Tagline / Slogan Kampanye
                </label>
                <input type="text" id="tagline" name="tagline" placeholder="Contoh: ICP Inklusif: Mengakselerasi Prestasi Internasional"
                    value="<?= htmlspecialchars($candidate['tagline'] ?? '') ?>"
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm">
            </div>

            <!-- Foto Calon -->
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Foto Resmi Kandidat
                </label>

                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <?php if (!empty($candidate['photo'])): ?>
                        <div class="relative w-20 h-20 rounded-xl overflow-hidden bg-slate-800 border border-slate-700 flex-shrink-0">
                            <img src="<?= htmlspecialchars($candidate['photo']) ?>" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <div class="flex-1 w-full space-y-2">
                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1">Unggah file foto (JPG, PNG, WEBP, SVG):</span>
                            <input type="file" name="photo" accept="image/*"
                                class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1">Atau masukkan URL / path foto:</span>
                            <input type="text" name="photo_url" placeholder="/assets/uploads/calon1.svg atau https://..."
                                value="<?= htmlspecialchars($candidate['photo'] ?? '') ?>"
                                class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-xs placeholder-slate-500 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visi -->
            <div>
                <label for="vision" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Visi Calon <span class="text-red-400">*</span>
                </label>
                <textarea id="vision" name="vision" required rows="3" placeholder="Tuliskan visi besar calon ketua..."
                    class="w-full p-4 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm leading-relaxed"><?= htmlspecialchars($candidate['vision'] ?? '') ?></textarea>
            </div>

            <!-- Misi -->
            <div>
                <label for="mission" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Misi Calon (Poin-Poin) <span class="text-red-400">*</span>
                </label>
                <textarea id="mission" name="mission" required rows="5" placeholder="1. Mengoptimalkan program mentoring akademik&#10;2. Meningkatkan sinergi antar-angkatan&#10;3. Memfasilitasi kompetisi internasional"
                    class="w-full p-4 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm leading-relaxed"><?= htmlspecialchars($candidate['mission'] ?? '') ?></textarea>
                <span class="text-[11px] text-slate-500 mt-1 block">Tuliskan tiap butir misi pada baris baru (enter).</span>
            </div>

            <!-- Program Kerja Unggulan -->
            <div>
                <label for="programs" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Program Kerja Unggulan (Opsional)
                </label>
                <textarea id="programs" name="programs" rows="3" placeholder="• ICP Global Mentorship & Career Accelerator&#10;• International Case Training Camp"
                    class="w-full p-4 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm leading-relaxed"><?= htmlspecialchars($candidate['programs'] ?? '') ?></textarea>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="/admin/candidates" class="px-5 py-2.5 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-xs shadow-lg shadow-blue-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Calon Ketua</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/admin_footer.php'; ?>
