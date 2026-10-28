<?php
$pageTitle = 'Halaman Tidak Ditemukan';
require __DIR__ . '/layout/header.php';
?>

<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-md mx-auto">
        <div class="w-20 h-20 rounded-2xl bg-slate-800 text-blue-500 flex items-center justify-center mx-auto mb-6 text-3xl font-black border border-slate-700">
            404
        </div>
        <h1 class="text-2xl font-black text-white mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-xs text-slate-400 mb-6">
            Halaman yang Anda tuju tidak tersedia atau alamat URL yang Anda masukkan keliru.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="/" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-600/30">
                Kembali ke Beranda
            </a>
            <a href="/voting" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold border border-slate-700 transition">
                Bilik Suara
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
