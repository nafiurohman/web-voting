<?php
namespace App\Controllers;

use App\Models\Candidate;
use App\Models\Vote;

class VotingController
{
    public function index(): void
    {
        $candidates = Candidate::getAll();
        $totalVotes = Vote::getTotalVotes();

        require __DIR__ . '/../../public/views/voting.php';
    }

    public function checkVoter(): void
    {
        header('Content-Type: application/json');

        $nama = trim($_POST['nama'] ?? '');
        $nim = trim($_POST['nim'] ?? '');

        if (empty($nama) || empty($nim)) {
            echo json_encode(['allowed' => false, 'message' => 'Nama lengkap dan NIM wajib diisi.']);
            return;
        }

        if (strlen($nama) < 3) {
            echo json_encode(['allowed' => false, 'message' => 'Nama minimal terdiri dari 3 karakter.']);
            return;
        }

        if (strlen($nim) < 3) {
            echo json_encode(['allowed' => false, 'message' => 'Nomor Induk Mahasiswa (NIM) tidak valid.']);
            return;
        }

        $alreadyVoted = Vote::hasVoted($nim);
        if ($alreadyVoted) {
            echo json_encode([
                'allowed' => false,
                'message' => "NIM '{$nim}' sudah tercatat pernah memberikan suara sebelumnya! 1 NIM hanya berhak memilih 1 kali."
            ]);
            return;
        }

        echo json_encode([
            'allowed' => true,
            'message' => 'Data valid. Silakan lanjutkan memilih kandidat pilihan Anda.'
        ]);
    }

    public function submitVote(): void
    {
        header('Content-Type: application/json');

        $nama = trim($_POST['nama'] ?? '');
        $nim = trim($_POST['nim'] ?? '');
        $candidateId = (int)($_POST['candidate_id'] ?? 0);

        if (empty($nama) || empty($nim) || $candidateId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Harap lengkapi identitas (Nama & NIM) dan pilih salah satu kandidat.']);
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $result = Vote::castVote($nama, $nim, $candidateId, $ip, $ua);

        echo json_encode($result);
    }
}
