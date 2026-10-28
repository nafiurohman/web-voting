<?php
$pageTitle = 'Login Panitia Pemilihan';
require __DIR__ . '/layout/header.php';
?>

<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Logo / Icon -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center mx-auto shadow-xl shadow-blue-600/30 text-2xl mb-3">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="text-2xl font-black text-white">Login Panitia Pemilihan</h1>
            <p class="text-xs text-slate-400 mt-1">Khusus panitia untuk mengelola calon, visi misi, &amp; data suara</p>
        </div>

        <!-- Flash Error -->
        <?php if (!empty($error)): ?>
            <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-semibold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Card -->
        <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-2xl">
            <form action="/login" method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" id="username" name="username" required placeholder="Masukkan username panitia"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password panitia"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk ke Panel Panitia</span>
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="/voting" class="text-xs text-slate-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Bilik Suara
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
