<?php
$currentUser = $_SESSION['user'] ?? null;
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Panel Admin Panitia ICP</title>
    <!-- FontAwesome 6 (Local Offline) -->
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <!-- Tailwind CSS (Local Runtime Compiler) -->
    <script src="/assets/tailwind.js"></script>
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
    <!-- Chart.js (Local Offline) -->
    <script src="/assets/chart.umd.min.js"></script>
    <!-- Standalone Local CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
<body class="min-h-full flex bg-slate-950 text-slate-100 antialiased">
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-950/80 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Admin Sidebar Navigation -->
    <aside id="adminSidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
        <div>
            <!-- Sidebar Header & Logo -->
            <div class="h-16 px-5 border-b border-slate-800 flex items-center justify-between">
                <a href="/admin/dashboard" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-md shadow-blue-500/20 flex-shrink-0">
                        <i class="fa-solid fa-check-to-slot text-white text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-white text-sm tracking-tight">ICP E-VOTING</span>
                            <span class="px-1.5 py-0.2 text-[9px] font-bold bg-blue-500/20 text-blue-400 rounded border border-blue-500/30">26/27</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Panel Panitia Pemilihan</p>
                    </div>
                </a>
                <button type="button" id="closeSidebarBtn" class="md:hidden text-slate-400 hover:text-white p-1 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="px-3 py-5 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">
                <!-- Group 1: Menu Utama -->
                <div>
                    <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Menu Utama</span>
                    <nav class="space-y-1">
                        <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($currentPath === '/admin/dashboard' || $currentPath === '/dashboard' || $currentPath === '/admin') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                            <i class="fa-solid fa-gauge-high w-4 text-center text-sm <?= ($currentPath === '/admin/dashboard' || $currentPath === '/dashboard') ? 'text-white' : 'text-blue-400' ?>"></i>
                            <span>Dashboard Admin</span>
                        </a>

                        <a href="/live-count" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= $currentPath === '/live-count' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-pie w-4 text-center text-sm <?= $currentPath === '/live-count' ? 'text-white' : 'text-emerald-400' ?>"></i>
                                <span>Live Quick Count</span>
                            </div>
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                LIVE
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Group 2: Manajemen Pemilihan -->
                <div>
                    <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Manajemen Pemilihan</span>
                    <nav class="space-y-1">
                        <a href="/admin/candidates" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= str_starts_with($currentPath, '/admin/candidates') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                            <i class="fa-solid fa-user-tie w-4 text-center text-sm <?= str_starts_with($currentPath, '/admin/candidates') ? 'text-white' : 'text-indigo-400' ?>"></i>
                            <span>Kelola Calon &amp; Visi Misi</span>
                        </a>

                        <a href="/admin/voters" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= $currentPath === '/admin/voters' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                            <i class="fa-solid fa-id-card w-4 text-center text-sm <?= $currentPath === '/admin/voters' ? 'text-white' : 'text-amber-400' ?>"></i>
                            <span>Data Pemilih &amp; Log NIM</span>
                        </a>

                        <a href="/voting" target="_blank" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-800/80 transition">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-person-booth w-4 text-center text-sm text-cyan-400"></i>
                                <span>Buka Bilik Suara</span>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500"></i>
                        </a>
                    </nav>
                </div>

                <!-- Group 3: Unduh Dokumen / Ekspor -->
                <div>
                    <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Laporan &amp; Ekspor</span>
                    <nav class="space-y-1">
                        <a href="/export/pdf" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-300 hover:text-white hover:bg-rose-950/40 border border-rose-500/20 transition">
                            <i class="fa-solid fa-file-pdf w-4 text-center text-sm text-rose-400"></i>
                            <span>Download Laporan (PDF)</span>
                        </a>
                        <a href="/export/xlsx" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-emerald-300 hover:text-white hover:bg-emerald-950/40 border border-emerald-500/20 transition">
                            <i class="fa-solid fa-file-excel w-4 text-center text-sm text-emerald-400"></i>
                            <span>Download Data (Excel)</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer & Admin User -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center font-black text-xs flex-shrink-0">
                        <?= strtoupper(substr($currentUser['username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-white truncate"><?= htmlspecialchars($currentUser['name'] ?? 'Administrator') ?></div>
                        <div class="text-[10px] text-slate-400 font-mono">@<?= htmlspecialchars($currentUser['username'] ?? 'icp') ?></div>
                    </div>
                </div>

                <a href="/logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition" title="Logout dari sistem">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:pl-64 flex flex-col min-h-screen">
        <!-- Topbar Header -->
        <header class="sticky top-0 z-30 h-16 bg-slate-900/90 border-b border-slate-800 backdrop-blur-md px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" id="openSidebarBtn" class="md:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-400">
                    <span class="hidden sm:inline font-semibold text-slate-300">Admin</span>
                    <span class="hidden sm:inline text-slate-600">/</span>
                    <span class="font-bold text-white"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
                </div>
            </div>

            <!-- Topbar Quick Action Pills -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="/live-count" class="px-3 py-1.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-live"></span>
                    <span>Live Quick Count</span>
                </a>
                <a href="/voting" target="_blank" class="hidden sm:flex px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition items-center gap-1.5 shadow-md shadow-blue-600/20">
                    <i class="fa-solid fa-vote-yea text-xs"></i>
                    <span>Bilik Suara</span>
                </a>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
