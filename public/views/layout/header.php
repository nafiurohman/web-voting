<?php
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Pemilihan Ketua ICP 2026-2027</title>
    <!-- Standalone Local CSS (Always loaded, no external blocking) -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .pulse-live {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-ring {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.15); }
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 selection:bg-blue-600 selection:text-white">
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 glass-panel">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <a href="/voting" class="flex items-center gap-2.5 sm:gap-3 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition flex-shrink-0">
                        <i class="fa-solid fa-check-to-slot text-white text-base sm:text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="font-extrabold text-sm sm:text-lg text-white tracking-tight">ICP E-VOTING</span>
                            <span class="px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-semibold bg-blue-500/20 text-blue-400 rounded-full border border-blue-500/30">26/27</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] text-slate-400 font-medium truncate max-w-[190px] sm:max-w-none">Pemilihan Ketua ICP 2026–2027</p>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <?php if ($currentUser): ?>
                        <a href="/dashboard" class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= ($path ?? '') === '/dashboard' ? 'text-white bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                            <span class="relative inline-flex items-center mr-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="absolute w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                            </span>
                            Quick Count
                        </a>
                        <a href="/voting" class="px-3 py-2 rounded-lg text-sm font-medium transition <?= ($path ?? '') === '/voting' ? 'text-white bg-blue-600 shadow-md shadow-blue-600/30' : 'text-blue-400 hover:bg-blue-600/10' ?>">
                            <i class="fa-solid fa-vote-yea mr-1.5"></i> Bilik Suara
                        </a>
                        <a href="/admin/candidates" class="px-3 py-2 rounded-lg text-sm font-medium transition <?= str_starts_with(($path ?? ''), '/admin/candidates') ? 'text-white bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                            <i class="fa-solid fa-user-pen mr-1.5 text-slate-400"></i> Kelola Calon
                        </a>
                        <a href="/admin/voters" class="px-3 py-2 rounded-lg text-sm font-medium transition <?= ($path ?? '') === '/admin/voters' ? 'text-white bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Daftar Pemilih
                        </a>
                    <?php else: ?>
                        <a href="/voting" class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-blue-600 shadow-md shadow-blue-600/30 flex items-center gap-2">
                            <i class="fa-solid fa-vote-yea"></i> Bilik Suara
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Right Action / Admin -->
                <div class="flex items-center gap-3">
                    <?php if ($currentUser): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400 font-semibold hidden sm:inline">Admin (<b><?= htmlspecialchars($currentUser['username']) ?></b>)</span>
                            <a href="/logout" class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition" title="Logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg text-xs font-semibold border border-slate-700 flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                            <span>Login Panitia</span>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobileMenuBtn" class="md:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 pt-2 border-t border-slate-800/60 flex flex-col gap-1">
                <?php if ($currentUser): ?>
                    <a href="/dashboard" class="px-3 py-2 rounded-lg text-sm font-semibold text-emerald-400 hover:bg-slate-800">Quick Count (Live)</a>
                    <a href="/voting" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800">Bilik Suara</a>
                    <a href="/admin/candidates" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800">Kelola Calon</a>
                    <a href="/admin/voters" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800">Daftar Pemilih</a>
                    <a href="/logout" class="px-3 py-2 rounded-lg text-sm font-medium text-red-400 hover:bg-slate-800">Logout</a>
                <?php else: ?>
                    <a href="/voting" class="px-3 py-2 rounded-lg text-sm font-semibold text-blue-400 hover:bg-slate-800">Bilik Suara (Voting)</a>
                    <a href="/login" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800">Login Panitia</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>

    <main class="flex-1">
