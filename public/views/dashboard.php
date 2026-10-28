<?php
$pageTitle = 'Live Quick Count & Dashboard';
require __DIR__ . '/layout/header.php';

$leaderCandidate = !empty($candidates) ? $candidates[0] : null;

$candCount = count($candidates);
$gridColClass = match($candCount) {
    1 => 'grid-cols-1 max-w-md mx-auto',
    2 => 'grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto',
    3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto',
    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 max-w-7xl mx-auto',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 max-w-7xl mx-auto',
};
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Top Action & Realtime Status Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 sm:mb-8 pb-5 sm:pb-6 border-b border-slate-800">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    LIVE REALTIME QUICK COUNT
                </span>
                <span class="hidden sm:inline text-xs text-slate-500">|</span>
                <span id="liveTimestamp" class="text-xs text-slate-400 font-mono">Sinkronisasi: <?= date('H:i:s') ?> WIB</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Dashboard Pemilihan Ketua ICP <span class="text-blue-500">2026–2027</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Data tabulasi suara diperbarui secara otomatis dan realtime via Server-Sent Events (SSE).</p>
        </div>

        <!-- Download & Navigation Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="/export/pdf" class="flex-1 sm:flex-none px-3.5 py-2.5 bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm" title="Download format PDF resmi">
                <i class="fa-solid fa-file-pdf text-rose-400"></i>
                <span>Download PDF</span>
            </a>
            <a href="/export/xlsx" class="flex-1 sm:flex-none px-3.5 py-2.5 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm" title="Download format Excel XLSX">
                <i class="fa-solid fa-file-excel text-emerald-400"></i>
                <span>Download Excel</span>
            </a>
            <a href="/voting" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-md shadow-blue-600/30">
                <i class="fa-solid fa-vote-yea"></i>
                <span>Bilik Suara</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
        <!-- Stat 1: Total Suara -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Suara Masuk</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">
                    <i class="fa-solid fa-box-archive text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span id="statTotalVotes" class="text-3xl font-black text-white"><?= number_format($totalVotes, 0, ',', '.') ?></span>
                <span class="text-xs text-slate-400">suara sah</span>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-400">
                <i class="fa-solid fa-circle-check"></i>
                <span>Tervalidasi &amp; masuk tabulasi</span>
            </div>
        </div>

        <!-- Stat 2: Kandidat Unggul -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kandidat Unggul</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-sm"></i>
                </div>
            </div>
            <div>
                <div id="statLeaderName" class="text-lg font-bold text-white truncate">
                    <?= $leaderCandidate ? '#' . sprintf('%02d', $leaderCandidate['candidate_number']) . ' ' . htmlspecialchars($leaderCandidate['name']) : '-' ?>
                </div>
                <div class="text-xs font-semibold text-emerald-400 mt-1">
                    <span id="statLeaderVotes"><?= $leaderCandidate ? number_format($leaderCandidate['votes_count'], 0, ',', '.') : 0 ?></span> suara 
                    (<span id="statLeaderPct"><?= $leaderCandidate ? number_format($leaderCandidate['percentage'], 1) : 0 ?></span>%)
                </div>
            </div>
        </div>

        <!-- Stat 3: Validasi Pemilih NIM -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Validasi Pemilih</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-black text-amber-400">1 NIM = 1 Suara</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-400">
                Pencegahan ganda berbasis NIM unik
            </div>
        </div>

        <!-- Stat 4: Status Realtime -->
        <div class="glass-panel rounded-2xl p-5 border border-slate-800 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Realtime</span>
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center">
                    <i class="fa-solid fa-tower-broadcast text-sm"></i>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span id="connectionBadge" class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span id="connectionText" class="text-sm font-bold text-white">Streaming Aktif (SSE)</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-400">
                Docker Port: 2345 | MySQL 8.0
            </div>
        </div>
    </div>

    <!-- Dynamic Candidate Progress Cards (Matches Candidate Count Dynamically) -->
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-base font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-users-viewfinder text-blue-400"></i>
            <span>Perolehan Suara Pasangan Calon (<?= $candCount ?> Kandidat)</span>
        </h2>
        <span class="text-xs text-slate-400">Pembaruan Langsung</span>
    </div>

    <div class="grid <?= $gridColClass ?> gap-5 sm:gap-6 mb-8">
        <?php 
        $colors = [
            1 => ['bg' => 'bg-blue-600', 'badge' => 'bg-blue-500/20 text-blue-400 border-blue-500/30', 'text' => 'text-blue-400', 'bar' => 'bg-blue-600'],
            2 => ['bg' => 'bg-emerald-600', 'badge' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30', 'text' => 'text-emerald-400', 'bar' => 'bg-emerald-600'],
            3 => ['bg' => 'bg-amber-600', 'badge' => 'bg-amber-500/20 text-amber-400 border-amber-500/30', 'text' => 'text-amber-400', 'bar' => 'bg-amber-600'],
            4 => ['bg' => 'bg-purple-600', 'badge' => 'bg-purple-500/20 text-purple-400 border-purple-500/30', 'text' => 'text-purple-400', 'bar' => 'bg-purple-600'],
        ];
        foreach ($candidates as $c): 
            $theme = $colors[$c['candidate_number']] ?? ['bg' => 'bg-indigo-600', 'badge' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30', 'text' => 'text-indigo-400', 'bar' => 'bg-indigo-600'];
        ?>
            <div id="card-cand-<?= $c['id'] ?>" class="glass-panel rounded-2xl p-5 border border-slate-800 relative flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-black text-white <?= $theme['bg'] ?>">
                            NO. URUT <?= sprintf('%02d', $c['candidate_number']) ?>
                        </span>
                        <div class="text-right">
                            <span class="text-xl sm:text-2xl font-black text-white cand-votes-<?= $c['id'] ?>"><?= number_format($c['votes_count'], 0, ',', '.') ?></span>
                            <span class="text-xs text-slate-400">suara</span>
                        </div>
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-white mb-1 leading-snug"><?= htmlspecialchars($c['name']) ?></h3>
                    <?php if (!empty($c['tagline'])): ?>
                        <p class="text-xs text-slate-400 italic line-clamp-2 mb-3">"<?= htmlspecialchars($c['tagline']) ?>"</p>
                    <?php endif; ?>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/80">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                        <span class="text-slate-400">Persentase</span>
                        <span class="<?= $theme['text'] ?> text-sm font-black cand-pct-<?= $c['id'] ?>"><?= number_format($c['percentage'], 1) ?>%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-3.5 overflow-hidden p-0.5">
                        <div class="cand-bar-<?= $c['id'] ?> <?= $theme['bar'] ?> h-full rounded-full transition-all duration-500" style="width: <?= max(2, $c['percentage']) ?>%"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Section: Interactive Charts Row 1 (Bar Chart & Doughnut Chart) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Diagram 1: Bar Chart Perolehan Suara -->
        <div class="lg:col-span-2 glass-panel rounded-2xl p-5 sm:p-6 border border-slate-800 flex flex-col">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center">
                        <i class="fa-solid fa-chart-column text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-white">Grafik Perolehan Suara Kandidat</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400">Visualisasi jumlah suara riil masing-masing calon ketua</p>
                    </div>
                </div>
                <span class="text-[11px] px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 font-medium">Interaktif</span>
            </div>
            <div class="h-64 sm:h-72 w-full relative flex-1">
                <canvas id="barChartCanvas"></canvas>
            </div>
        </div>

        <!-- Diagram 2: Doughnut Chart Persentase (%) -->
        <div class="glass-panel rounded-2xl p-5 sm:p-6 border border-slate-800 flex flex-col">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600/20 text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-chart-pie text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-white">Proporsi Suara (%)</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400">Distribusi persentase perolehan suara sah</p>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-72 w-full relative flex-1 flex items-center justify-center">
                <canvas id="doughnutChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Section: Interactive Charts Row 2 (Timeline Activity & Live Feed) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Diagram 3: Aktivitas Pemungutan Suara per Jam -->
        <div class="lg:col-span-2 glass-panel rounded-2xl p-5 sm:p-6 border border-slate-800 flex flex-col">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600/20 text-indigo-400 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-white">Tren Waktu Pemungutan Suara</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400">Volume suara yang masuk dikelompokkan berdasarkan waktu (WIB)</p>
                    </div>
                </div>
                <span class="text-[11px] px-2.5 py-1 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-medium">Jam ke Jam</span>
            </div>
            <div class="h-64 sm:h-72 w-full relative flex-1">
                <canvas id="timelineChartCanvas"></canvas>
            </div>
        </div>

        <!-- Recent Voters Live Ticker Feed -->
        <div class="glass-panel rounded-2xl p-5 sm:p-6 border border-slate-800 flex flex-col">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    </span>
                    <h3 class="text-sm font-bold text-white">Suara Terkini (Live Feed)</h3>
                </div>
                <span class="text-[10px] text-slate-400 font-mono px-2 py-0.5 rounded bg-slate-800">Realtime</span>
            </div>

            <div id="recentVotersFeed" class="space-y-3 flex-1 overflow-y-auto max-h-72 pr-1 text-xs">
                <?php if (empty($recentVoters)): ?>
                    <div class="text-center py-10 text-slate-500 italic">Belum ada suara masuk. Jadilah pemilih pertama!</div>
                <?php else: ?>
                    <?php foreach ($recentVoters as $rv): ?>
                        <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    <?= strtoupper(substr($rv['voter_name'], 0, 1)) ?>
                                </div>
                                <div class="truncate">
                                    <div class="font-bold text-white truncate text-xs sm:text-sm"><?= htmlspecialchars($rv['voter_name']) ?></div>
                                    <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1">
                                        <span class="text-slate-500">NIM:</span> <?= htmlspecialchars($rv['nim']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    #<?= sprintf('%02d', $rv['candidate_number']) ?>
                                </span>
                                <div class="text-[10px] text-slate-500 mt-1"><?= date('H:i:s', strtotime($rv['voted_at'])) ?> WIB</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Initial Data from PHP backend
let initialData = {
    total_votes: <?= (int)$totalVotes ?>,
    candidates: <?= json_encode($candidates) ?>,
    timeline_stats: <?= json_encode($timelineStats) ?>,
    recent_voters: <?= json_encode($recentVoters) ?>
};

// Candidate Palette
const palette = [
    { bg: '#3b82f6', border: '#2563eb' },
    { bg: '#10b981', border: '#059669' },
    { bg: '#f59e0b', border: '#d97706' },
    { bg: '#8b5cf6', border: '#7c3aed' },
    { bg: '#ec4899', border: '#db2777' },
    { bg: '#06b6d4', border: '#0891b2' }
];

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Chart Instances
let barChart = null;
let doughnutChart = null;
let timelineChart = null;

function initCharts() {
    // 1. Bar Chart: Candidate Votes
    const barCtx = document.getElementById('barChartCanvas').getContext('2d');
    const candLabels = (initialData.candidates || []).map(c => '#' + String(c.candidate_number).padStart(2, '0') + ' ' + (c.name.length > 18 ? c.name.substring(0, 18) + '...' : c.name));
    const candVotes = (initialData.candidates || []).map(c => c.votes_count);
    const candColors = (initialData.candidates || []).map((c, i) => palette[i % palette.length].bg);

    barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: candLabels,
            datasets: [{
                label: 'Jumlah Suara',
                data: candVotes,
                backgroundColor: candColors,
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 36
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#cbd5e1',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            const val = context.parsed.y;
                            const total = initialData.total_votes || 1;
                            const pct = ((val / total) * 100).toFixed(1);
                            return ` ${val} suara (${pct}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8',
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#e2e8f0',
                        font: { weight: '600', size: 11 }
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Doughnut Chart: Percentage Distribution
    const doughnutCtx = document.getElementById('doughnutChartCanvas').getContext('2d');
    doughnutChart = new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: candLabels,
            datasets: [{
                data: candVotes.length && initialData.total_votes > 0 ? candVotes : [1],
                backgroundColor: initialData.total_votes > 0 ? candColors : ['#334155'],
                borderWidth: 2,
                borderColor: '#0f172a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#cbd5e1',
                        font: { size: 11 },
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    borderColor: '#334155',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            if (initialData.total_votes === 0) return ' Belum ada suara masuk';
                            const val = context.parsed;
                            const total = initialData.total_votes;
                            const pct = ((val / total) * 100).toFixed(1);
                            return ` ${context.label}: ${val} suara (${pct}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // 3. Timeline Chart: Votes over time
    const timelineCtx = document.getElementById('timelineChartCanvas').getContext('2d');
    const tlLabels = (initialData.timeline_stats || []).map(t => t.time_slot + ' WIB');
    const tlData = (initialData.timeline_stats || []).map(t => parseInt(t.total_votes, 10));

    timelineChart = new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: tlLabels.length ? tlLabels : [new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) + ' WIB'],
            datasets: [{
                label: 'Suara per Jam',
                data: tlData.length ? tlData : [0],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.15)',
                borderWidth: 2.5,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#818cf8',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    borderColor: '#334155',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ` ${context.parsed.y} suara masuk`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#94a3b8', stepSize: 1 },
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }
                },
                x: {
                    ticks: { color: '#e2e8f0', font: { size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });
}

// Update UI with new incoming realtime data
function updateDashboardUI(data) {
    initialData = data;

    // Update timestamp
    document.getElementById('liveTimestamp').textContent = 'Sinkronisasi: ' + (data.timestamp || new Date().toLocaleTimeString('id-ID')) + ' WIB';

    // Update total votes
    document.getElementById('statTotalVotes').textContent = Number(data.total_votes).toLocaleString('id-ID');

    // Update leader
    if (data.candidates && data.candidates.length > 0) {
        const sorted = [...data.candidates].sort((a, b) => b.votes_count - a.votes_count);
        const top = sorted[0];
        document.getElementById('statLeaderName').textContent = '#' + String(top.candidate_number).padStart(2, '0') + ' ' + top.name;
        document.getElementById('statLeaderVotes').textContent = Number(top.votes_count).toLocaleString('id-ID');
        document.getElementById('statLeaderPct').textContent = Number(top.percentage).toFixed(1);
    }

    // Update Candidate Progress Cards
    if (data.candidates) {
        data.candidates.forEach(c => {
            const votesEl = document.querySelector(`.cand-votes-${c.id}`);
            const pctEl = document.querySelector(`.cand-pct-${c.id}`);
            const barEl = document.querySelector(`.cand-bar-${c.id}`);

            if (votesEl) votesEl.textContent = Number(c.votes_count).toLocaleString('id-ID');
            if (pctEl) pctEl.textContent = Number(c.percentage).toFixed(1) + '%';
            if (barEl) barEl.style.width = Math.max(2, c.percentage) + '%';
        });
    }

    // Update Charts
    if (barChart && data.candidates) {
        barChart.data.datasets[0].data = data.candidates.map(c => c.votes_count);
        barChart.update('active');
    }

    if (doughnutChart && data.candidates) {
        const votes = data.candidates.map(c => c.votes_count);
        const candColors = data.candidates.map((c, i) => palette[i % palette.length].bg);
        if (data.total_votes > 0) {
            doughnutChart.data.datasets[0].data = votes;
            doughnutChart.data.datasets[0].backgroundColor = candColors;
        } else {
            doughnutChart.data.datasets[0].data = [1];
            doughnutChart.data.datasets[0].backgroundColor = ['#334155'];
        }
        doughnutChart.update();
    }

    if (timelineChart && data.timeline_stats) {
        timelineChart.data.labels = data.timeline_stats.map(t => t.time_slot + ' WIB');
        timelineChart.data.datasets[0].data = data.timeline_stats.map(t => parseInt(t.total_votes, 10));
        timelineChart.update();
    }

    // Update Recent Voters Feed
    if (data.recent_voters) {
        const feed = document.getElementById('recentVotersFeed');
        if (data.recent_voters.length === 0) {
            feed.innerHTML = '<div class="text-center py-10 text-slate-500 italic">Belum ada suara masuk. Jadilah pemilih pertama!</div>';
        } else {
            feed.innerHTML = data.recent_voters.map(rv => `
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                            ${(rv.voter_name || 'M').charAt(0).toUpperCase()}
                        </div>
                        <div class="truncate">
                            <div class="font-bold text-white truncate text-xs sm:text-sm">${escapeHtml(rv.voter_name)}</div>
                            <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1">
                                <span class="text-slate-500">NIM:</span> ${escapeHtml(rv.nim)}
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            #${String(rv.candidate_number).padStart(2, '0')}
                        </span>
                        <div class="text-[10px] text-slate-500 mt-1">${rv.voted_at ? rv.voted_at.substring(11, 19) + ' WIB' : ''}</div>
                    </div>
                </div>
            `).join('');
        }
    }
}

// Realtime Connection via Server-Sent Events (SSE)
function setupRealtimeStream() {
    const badge = document.getElementById('connectionBadge');
    const text = document.getElementById('connectionText');

    if (typeof EventSource !== 'undefined') {
        const evtSource = new EventSource('/api/stream');

        evtSource.addEventListener('vote_update', function(e) {
            try {
                const parsed = JSON.parse(e.data);
                updateDashboardUI(parsed);
            } catch (err) {
                console.error('Error parsing SSE:', err);
            }
        });

        evtSource.onopen = function() {
            badge.className = 'inline-block w-2.5 h-2.5 rounded-full bg-emerald-500';
            text.textContent = 'Streaming Aktif (SSE)';
        };

        evtSource.onerror = function() {
            badge.className = 'inline-block w-2.5 h-2.5 rounded-full bg-amber-500';
            text.textContent = 'Menghubungkan Ulang...';
        };
    }

    // Polling fallback every 3 seconds
    setInterval(async () => {
        try {
            const res = await fetch('/api/results');
            if (res.ok) {
                const json = await res.json();
                if (json.status === 'success' && json.data) {
                    updateDashboardUI(json.data);
                }
            }
        } catch (err) {
            // silent fail
        }
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    initCharts();
    setupRealtimeStream();
});
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>
