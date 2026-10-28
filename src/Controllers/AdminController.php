<?php
namespace App\Controllers;

use App\Models\Candidate;
use App\Models\Vote;

class AdminController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu untuk mengakses menu admin.';
            header('Location: /login');
            exit;
        }
    }

    public function dashboard(): void
    {
        $results = Vote::getResults();
        $timelineStats = Vote::getTimelineBreakdown();
        $recentVoters = Vote::getRecentVoters(6);
        $totalCandidates = count($results['candidates']);
        $totalVotes = $results['total_votes'];

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../../public/views/admin/dashboard.php';
    }

    public function candidates(): void
    {
        $candidates = Candidate::getAll();
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../../public/views/admin/candidates.php';
    }

    public function candidateCreate(): void
    {
        $candidate = null;
        $nextNumber = Candidate::getNextNumber();
        $isEdit = false;

        require __DIR__ . '/../../public/views/admin/candidate_form.php';
    }

    public function candidateEdit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $candidate = Candidate::getById($id);
        if (!$candidate) {
            $_SESSION['flash_error'] = 'Kandidat tidak ditemukan.';
            header('Location: /admin/candidates');
            exit;
        }

        $nextNumber = (int)$candidate['candidate_number'];
        $isEdit = true;

        require __DIR__ . '/../../public/views/admin/candidate_form.php';
    }

    public function saveCandidate(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $candidateNumber = (int)($_POST['candidate_number'] ?? 1);
        $name = trim($_POST['name'] ?? '');
        $tagline = trim($_POST['tagline'] ?? '');
        $vision = trim($_POST['vision'] ?? '');
        $mission = trim($_POST['mission'] ?? '');
        $programs = trim($_POST['programs'] ?? '');
        $existingPhoto = trim($_POST['existing_photo'] ?? '/assets/uploads/default.svg');

        if (empty($name) || empty($vision) || empty($mission)) {
            $_SESSION['flash_error'] = 'Nama calon, Visi, dan Misi wajib diisi.';
            header('Location: ' . ($id > 0 ? "/admin/candidates/edit?id={$id}" : '/admin/candidates/create'));
            exit;
        }

        // Handle Photo Upload if any
        $photoPath = $existingPhoto;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (in_array($ext, $allowed)) {
                $newFilename = 'calon_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $targetFile = $uploadDir . $newFilename;
                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    $photoPath = '/assets/uploads/' . $newFilename;
                }
            }
        } elseif (!empty($_POST['photo_url'])) {
            $photoPath = trim($_POST['photo_url']);
        }

        $data = [
            'candidate_number' => $candidateNumber,
            'name' => $name,
            'tagline' => $tagline,
            'photo' => $photoPath,
            'vision' => $vision,
            'mission' => $mission,
            'programs' => $programs
        ];

        if ($id > 0) {
            Candidate::update($id, $data);
            $_SESSION['flash_success'] = 'Data Kandidat #' . $candidateNumber . ' berhasil diperbarui!';
        } else {
            Candidate::create($data);
            $_SESSION['flash_success'] = 'Kandidat baru #' . $candidateNumber . ' berhasil ditambahkan dan siap dipilih!';
        }

        header('Location: /admin/candidates');
        exit;
    }

    public function deleteCandidate(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Candidate::delete($id);
            $_SESSION['flash_success'] = 'Kandidat berhasil dihapus.';
        }
        header('Location: /admin/candidates');
        exit;
    }

    public function voters(): void
    {
        $voters = Vote::getAllVoters();
        $totalVotes = count($voters);

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../../public/views/admin/voters.php';
    }

    public function deleteVoter(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Vote::deleteVoter($id);
            $_SESSION['flash_success'] = 'Data pemilih berhasil dihapus dan suara kandidat telah disesuaikan.';
        }
        header('Location: /admin/voters');
        exit;
    }

    public function clearAllVoters(): void
    {
        Vote::resetAllVotes();
        $_SESSION['flash_success'] = 'Seluruh daftar pemilih dan suara berhasil dibersihkan (dikosongkan).';
        header('Location: /admin/voters');
        exit;
    }

    public function resetElection(): void
    {
        Vote::resetAllVotes();
        $_SESSION['flash_success'] = 'Semua data pemilih & perolehan suara berhasil dibersihkan menjadi 0.';
        header('Location: /admin/dashboard');
        exit;
    }
}
