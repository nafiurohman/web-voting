<?php
$pageTitle = 'Bilik Suara (Voting)';
require __DIR__ . '/layout/header.php';

$candCount = count($candidates);
$gridColClass = match($candCount) {
    1 => 'grid-cols-1 max-w-md mx-auto',
    2 => 'grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto',
    3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto',
    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 max-w-7xl mx-auto',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 max-w-7xl mx-auto',
};
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Title -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold mb-3">
            <i class="fa-solid fa-lock"></i>
            <span>Sistem E-Voting Terenkripsi &amp; Terverifikasi</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Bilik Suara Pemilihan Ketua ICP</h1>
        <p class="text-slate-400 text-sm mt-2 max-w-xl mx-auto">
            Gunakan hak suara Anda. Masukkan identitas diri Anda, pelajari visi misi kandidat, dan pilih calon pemimpin masa depan ICP.
        </p>

        <!-- Stepper Indicator -->
        <div class="max-w-xl mx-auto mt-8 flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-800 -z-0"></div>
            <div id="stepProgress" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 transition-all duration-300 w-1/4 -z-0"></div>

            <!-- Step 1 Dot -->
            <div id="step1Indicator" class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shadow-lg shadow-blue-600/40 text-sm transition-all duration-300">
                    1
                </div>
                <span class="text-xs font-semibold text-blue-400 mt-2">Identitas</span>
            </div>

            <!-- Step 2 Dot -->
            <div id="step2Indicator" class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 border border-slate-700 font-bold flex items-center justify-center text-sm transition-all duration-300">
                    2
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Pilih Calon</span>
            </div>

            <!-- Step 3 Dot -->
            <div id="step3Indicator" class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 border border-slate-700 font-bold flex items-center justify-center text-sm transition-all duration-300">
                    3
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Alert Message Box -->
    <div id="alertBox" class="hidden max-w-xl mx-auto mb-6 p-4 rounded-xl border text-sm font-medium flex items-start gap-3 transition">
        <i id="alertIcon" class="fa-solid fa-circle-exclamation mt-0.5 text-lg"></i>
        <div id="alertMessage" class="flex-1"></div>
    </div>

    <!-- STEP 1: Input Identitas (Nama & NIM) -->
    <div id="step1Container" class="max-w-xl mx-auto">
        <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-2xl">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Langkah 1: Identitas Pemilih</h2>
                    <p class="text-xs text-slate-400">Pastikan data Anda sesuai dengan registrasi mahasiswa ICP aktif.</p>
                </div>
            </div>

            <form id="voterIdentityForm" onsubmit="handleProceedToStep2(event)" class="space-y-5">
                <div>
                    <label for="voterName" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nama Lengkap Mahasiswa <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <input type="text" id="voterName" name="voter_name" required placeholder="Contoh: Muhammad Al-Fatih"
                            class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition">
                    </div>
                </div>

                <div>
                    <label for="voterNim" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nomor Induk Mahasiswa (NIM) <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" id="voterNim" name="nim" required placeholder="Contoh: 202610115001"
                            class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition font-mono">
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">NIM berlaku sebagai kunci unik (1 NIM hanya boleh memilih 1 kali).</span>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnNextStep1"
                        class="w-full py-3.5 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                        <span>Lanjut ke Pemilihan Kandidat</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>

                <p class="text-[11px] text-slate-500 text-center leading-relaxed">
                    Sistem akan memvalidasi data Anda. Setiap mahasiswa hanya memiliki 1 hak suara sah untuk periode 2026–2027.
                </p>
            </form>
        </div>
    </div>

    <!-- STEP 2: Pilih Calon Ketua ICP -->
    <div id="step2Container" class="hidden">
        <!-- Voter Identity Badge Banner -->
        <div class="glass-panel rounded-2xl p-4 sm:p-5 mb-8 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Pemilih Terverifikasi:</div>
                    <div class="text-sm font-bold text-white">
                        <span id="badgeVoterName" class="text-blue-400"></span> 
                        <span class="text-slate-500">|</span> 
                        NIM: <span id="badgeVoterNim" class="text-slate-200 font-mono"></span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="backToStep1()" class="text-xs text-slate-400 hover:text-white px-3 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>Ubah Data Identitas</span>
            </button>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white">Silakan Pilih Kandidat Ketua ICP Anda</h2>
            <p class="text-xs text-slate-400 mt-1">Klik tombol "Visi &amp; Misi" untuk membaca visi lengkap atau langsung klik "Pilih Kandidat".</p>
        </div>

        <!-- Candidate Cards Grid (Dynamic columns based on candidate count) -->
        <div class="grid <?= $gridColClass ?> gap-6">
            <?php foreach ($candidates as $c): ?>
                <div class="glass-panel rounded-2xl overflow-hidden border border-slate-800 hover:border-blue-500/50 transition-all duration-300 flex flex-col group">
                    <!-- Photo Header -->
                    <div class="relative h-52 sm:h-64 bg-slate-900/90 p-4 flex items-center justify-center">
                        <img src="<?= htmlspecialchars($c['photo']) ?>" alt="<?= htmlspecialchars($c['name']) ?>" class="h-full w-full object-contain group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-4 left-4 px-3 py-1 rounded-xl bg-blue-600 text-white font-black text-sm tracking-wide shadow-lg shadow-blue-600/40 border border-blue-400/30">
                            NO. #<?= sprintf('%02d', $c['candidate_number']) ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">
                            <?= htmlspecialchars($c['name']) ?>
                        </h3>
                        <?php if (!empty($c['tagline'])): ?>
                            <p class="text-xs text-blue-400 font-medium italic mt-1">
                                "<?= htmlspecialchars($c['tagline']) ?>"
                            </p>
                        <?php endif; ?>

                        <!-- Vision Preview -->
                        <div class="mt-4 pt-3 border-t border-slate-800 flex-1">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Visi:</span>
                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                                <?= nl2br(htmlspecialchars($c['vision'])) ?>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 pt-4 border-t border-slate-800 space-y-2.5">
                            <button type="button" onclick="showVisiMisiModal(<?= htmlspecialchars(json_encode($c)) ?>)"
                                class="w-full py-2 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold border border-slate-700 transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-book-open text-xs"></i>
                                <span>Lihat Visi &amp; Misi Lengkap</span>
                            </button>

                            <button type="button" onclick="confirmVote(<?= htmlspecialchars(json_encode($c)) ?>)"
                                class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-bold shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i>
                                <span>PILIH KANDIDAT #<?= sprintf('%02d', $c['candidate_number']) ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STEP 3: Sukses & E-Receipt -->
    <div id="step3Container" class="hidden max-w-xl mx-auto text-center">
        <div class="glass-panel rounded-3xl p-8 border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg shadow-emerald-500/30">
                <i class="fa-solid fa-check"></i>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-2">Suara Berhasil Diberikan!</h2>
            <p class="text-sm text-slate-400 mb-6">
                Terima kasih telah berpartisipasi dalam Pemilihan Ketua ICP Periode 2026–2027. Suara Anda telah terverifikasi secara sah.
            </p>

            <!-- E-Receipt Box -->
            <div class="bg-slate-900/80 rounded-2xl p-5 border border-slate-800 text-left mb-6 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-xs text-slate-500">Nama Pemilih</span>
                    <span id="receiptVoterName" class="text-sm font-bold text-white"></span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-xs text-slate-500">NIM Mahasiswa</span>
                    <span id="receiptNim" class="text-sm font-semibold text-slate-300 font-mono"></span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span class="text-xs text-slate-500">Kandidat Pilihan</span>
                    <span id="receiptCandidate" class="text-sm font-bold text-blue-400"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Waktu Transaksi</span>
                    <span id="receiptTime" class="text-xs text-slate-400 font-mono"></span>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/voting" class="flex-1 py-3 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-vote-yea"></i>
                    <span>Selesai / Pemilih Berikutnya</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Visi & Misi Lengkap -->
<div id="visiMisiModal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="glass-panel w-full max-w-2xl rounded-2xl border border-slate-700 shadow-2xl p-4 sm:p-8 relative my-auto max-h-[92vh] flex flex-col">
        <button type="button" onclick="closeVisiMisiModal()" class="absolute top-4 right-4 sm:top-5 sm:right-5 text-slate-400 hover:text-white p-2 text-base">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-3 mb-4 pb-3 sm:mb-6 sm:pb-4 border-b border-slate-800 pr-8">
            <div id="modalNumberBadge" class="px-2.5 py-1 bg-blue-600 text-white font-black text-xs sm:text-sm rounded-lg flex-shrink-0"></div>
            <div>
                <h3 id="modalCandidateName" class="text-base sm:text-xl font-bold text-white"></h3>
                <p id="modalTagline" class="text-xs text-blue-400 italic"></p>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-6 overflow-y-auto pr-1 text-xs sm:text-sm text-slate-300 flex-1">
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-compass text-blue-400"></i>
                    <span>Visi Calon:</span>
                </h4>
                <div id="modalVision" class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 leading-relaxed"></div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-emerald-400"></i>
                    <span>Misi Calon:</span>
                </h4>
                <div id="modalMission" class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 whitespace-pre-line leading-relaxed"></div>
            </div>

            <div id="modalProgramsWrapper">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-star text-amber-400"></i>
                    <span>Program Kerja Unggulan:</span>
                </h4>
                <div id="modalPrograms" class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 whitespace-pre-line leading-relaxed"></div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
            <button type="button" onclick="closeVisiMisiModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal: Konfirmasi Pilihan Suara -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="glass-panel w-full max-w-md rounded-2xl border border-slate-700 shadow-2xl p-5 sm:p-7 text-center my-auto">
        <div class="w-16 h-16 rounded-2xl bg-blue-600/20 text-blue-400 border border-blue-500/40 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h3 class="text-xl font-bold text-white mb-1">Konfirmasi Pilihan Suara</h3>
        <p class="text-xs text-slate-400 mb-6">Pastikan Anda memilih kandidat yang sesuai dengan keyakinan Anda.</p>

        <div class="bg-slate-900/90 rounded-2xl p-4 border border-slate-800 mb-6 text-left">
            <div class="text-[11px] text-slate-400 mb-1">Kandidat yang akan Anda pilih:</div>
            <div class="flex items-center gap-3">
                <span id="confirmNoUrut" class="px-2.5 py-1 bg-blue-600 text-white font-black text-xs rounded-lg"></span>
                <span id="confirmName" class="font-bold text-white text-sm"></span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                <span>Pemilih:</span>
                <span id="confirmVoterSummary" class="font-semibold text-slate-200"></span>
            </div>
        </div>

        <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs mb-6 text-left flex items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <span>Pilihan bersifat rahasia dan <b>tidak dapat diubah</b> setelah tombol konfirmasi diklik.</span>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="closeConfirmModal()" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-semibold transition">
                Batal
            </button>
            <button type="button" id="btnSubmitVote" onclick="submitFinalVote()" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition">
                Ya, Kirim Suara!
            </button>
        </div>
    </div>
</div>

<script>
let currentVoter = {
    nama: '',
    nim: ''
};
let selectedCandidate = null;

function showAlert(message, type = 'error') {
    const box = document.getElementById('alertBox');
    const msg = document.getElementById('alertMessage');
    const icon = document.getElementById('alertIcon');

    box.classList.remove('hidden', 'bg-red-500/10', 'border-red-500/30', 'text-red-400', 'bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
    if (type === 'error') {
        box.classList.add('bg-red-500/10', 'border-red-500/30', 'text-red-400');
        icon.className = 'fa-solid fa-circle-exclamation mt-0.5 text-lg';
    } else {
        box.classList.add('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
        icon.className = 'fa-solid fa-circle-check mt-0.5 text-lg';
    }
    msg.textContent = message;
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideAlert() {
    document.getElementById('alertBox').classList.add('hidden');
}

async function handleProceedToStep2(e) {
    e.preventDefault();
    hideAlert();

    const nameInput = document.getElementById('voterName');
    const nimInput = document.getElementById('voterNim');
    const btn = document.getElementById('btnNextStep1');

    const nama = nameInput.value.trim();
    const nim = nimInput.value.trim();

    if (!nama || !nim) {
        showAlert('Silakan lengkapi Nama Lengkap dan NIM Anda.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Memverifikasi NIM...</span>';

    try {
        const formData = new FormData();
        formData.append('nama', nama);
        formData.append('nim', nim);

        const res = await fetch('/api/check-voter', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (!data.allowed) {
            showAlert(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<span>Lanjut ke Pemilihan Kandidat</span><i class="fa-solid fa-arrow-right text-xs"></i>';
            return;
        }

        // Success - Proceed to Step 2
        currentVoter.nama = nama;
        currentVoter.nim = nim;

        document.getElementById('badgeVoterName').textContent = nama;
        document.getElementById('badgeVoterNim').textContent = nim;

        // Switch containers
        document.getElementById('step1Container').classList.add('hidden');
        document.getElementById('step2Container').classList.remove('hidden');

        // Update Stepper
        document.getElementById('stepProgress').style.width = '50%';
        const step2Dot = document.getElementById('step2Indicator').querySelector('div');
        step2Dot.className = 'w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shadow-lg shadow-blue-600/40 text-sm transition-all duration-300';
        document.getElementById('step2Indicator').querySelector('span').className = 'text-xs font-semibold text-blue-400 mt-2';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (err) {
        showAlert('Terjadi kesalahan saat memeriksa NIM pemilih. Pastikan koneksi server aktif.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span>Lanjut ke Pemilihan Kandidat</span><i class="fa-solid fa-arrow-right text-xs"></i>';
    }
}

function backToStep1() {
    document.getElementById('step2Container').classList.add('hidden');
    document.getElementById('step1Container').classList.remove('hidden');
    document.getElementById('stepProgress').style.width = '25%';
    const step2Dot = document.getElementById('step2Indicator').querySelector('div');
    step2Dot.className = 'w-10 h-10 rounded-full bg-slate-800 text-slate-400 border border-slate-700 font-bold flex items-center justify-center text-sm transition-all duration-300';
    document.getElementById('step2Indicator').querySelector('span').className = 'text-xs font-semibold text-slate-500 mt-2';
}

function showVisiMisiModal(cand) {
    document.getElementById('modalNumberBadge').textContent = 'NO. #' + String(cand.candidate_number).padStart(2, '0');
    document.getElementById('modalCandidateName').textContent = cand.name;
    document.getElementById('modalTagline').textContent = cand.tagline ? `"${cand.tagline}"` : '';
    document.getElementById('modalVision').textContent = cand.vision;
    document.getElementById('modalMission').textContent = cand.mission;

    const progWrap = document.getElementById('modalProgramsWrapper');
    if (cand.programs && cand.programs.trim()) {
        progWrap.classList.remove('hidden');
        document.getElementById('modalPrograms').textContent = cand.programs;
    } else {
        progWrap.classList.add('hidden');
    }

    document.getElementById('visiMisiModal').classList.remove('hidden');
}

function closeVisiMisiModal() {
    document.getElementById('visiMisiModal').classList.add('hidden');
}

function confirmVote(cand) {
    selectedCandidate = cand;
    document.getElementById('confirmNoUrut').textContent = '#' + String(cand.candidate_number).padStart(2, '0');
    document.getElementById('confirmName').textContent = cand.name;
    document.getElementById('confirmVoterSummary').textContent = `${currentVoter.nama} (NIM: ${currentVoter.nim})`;

    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

async function submitFinalVote() {
    if (!selectedCandidate || !currentVoter.nim) return;

    const btn = document.getElementById('btnSubmitVote');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';

    try {
        const formData = new FormData();
        formData.append('nama', currentVoter.nama);
        formData.append('nim', currentVoter.nim);
        formData.append('candidate_id', selectedCandidate.id);

        const res = await fetch('/api/vote', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (!data.success) {
            closeConfirmModal();
            showAlert(data.message, 'error');
            return;
        }

        // Close confirm modal
        closeConfirmModal();

        // Switch to Step 3 (Success)
        document.getElementById('step2Container').classList.add('hidden');
        document.getElementById('step3Container').classList.remove('hidden');

        // Fill receipt
        document.getElementById('receiptVoterName').textContent = currentVoter.nama;
        document.getElementById('receiptNim').textContent = currentVoter.nim;
        document.getElementById('receiptCandidate').textContent = '#' + String(selectedCandidate.candidate_number).padStart(2, '0') + ' ' + selectedCandidate.name;
        document.getElementById('receiptTime').textContent = new Date().toLocaleString('id-ID');

        // Update Stepper to 100%
        document.getElementById('stepProgress').style.width = '100%';
        const step3Dot = document.getElementById('step3Indicator').querySelector('div');
        step3Dot.className = 'w-10 h-10 rounded-full bg-emerald-500 text-white font-bold flex items-center justify-center shadow-lg shadow-emerald-500/40 text-sm transition-all duration-300';
        document.getElementById('step3Indicator').querySelector('span').className = 'text-xs font-semibold text-emerald-400 mt-2';

        // Trigger Confetti!
        confetti({
            particleCount: 120,
            spread: 70,
            origin: { y: 0.6 }
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (err) {
        closeConfirmModal();
        showAlert('Terjadi kesalahan jaringan saat mengirimkan suara.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Ya, Kirim Suara!';
    }
}
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>
