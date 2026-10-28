        </main>

        <!-- Admin Footer -->
        <footer class="border-t border-slate-800/80 px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; <?= date('Y') ?> Pemilihan Ketua ICP Periode 2026–2027.
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-mono text-[11px]">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Server Docker Port 2345
                </span>
                <span class="text-slate-600">|</span>
                <a href="/live-count" class="text-blue-400 hover:underline">Quick Count</a>
            </div>
        </footer>
    </div>

    <!-- Mobile Sidebar Script -->
    <script>
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const openSidebarBtn = document.getElementById('openSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        function openSidebar() {
            adminSidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            adminSidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }

        openSidebarBtn?.addEventListener('click', openSidebar);
        closeSidebarBtn?.addEventListener('click', closeSidebar);
        sidebarBackdrop?.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
