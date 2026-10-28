    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-950/80 py-8 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-300">Komisi Pemilihan Umum Mahasiswa ICP</div>
                        <div>International Class Program Periode 2026–2027</div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 text-slate-400">
                    <a href="/voting" class="hover:text-blue-400 transition">Bilik Suara</a>
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a href="/dashboard" class="hover:text-blue-400 transition">Quick Count Realtime</a>
                        <a href="/export/pdf" class="hover:text-blue-400 transition">Unduh PDF</a>
                        <a href="/export/xlsx" class="hover:text-blue-400 transition">Unduh Excel</a>
                        <a href="/logout" class="hover:text-red-400 transition">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="hover:text-blue-400 transition">Login Panitia</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-800/40 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px]">
                <p>&copy; <?= date('Y') ?> Komisi Pemilihan Ketua ICP. Transparan, Akuntabel, dan Realtime.</p>
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Docker Container Port: 2345 | Engine: PHP 8.2 + MySQL</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
