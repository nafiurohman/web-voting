<?php
namespace App\Controllers;

use App\Models\Vote;
use App\Models\Candidate;
use App\Utils\PdfReport;
use App\Utils\SimpleXLSXWriter;

class ExportController
{
    private string $downloadDir;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Unduh laporan PDF/Excel hanya dapat diakses oleh Panitia/Admin yang sudah login.';
            header('Location: /login');
            exit;
        }

        $this->downloadDir = __DIR__ . '/../../downloads/';
        if (!is_dir($this->downloadDir)) {
            mkdir($this->downloadDir, 0777, true);
        }
    }

    public function exportPdf(): void
    {
        $results = Vote::getResults();
        $voters = Vote::getAllVoters();
        $totalVotes = $results['total_votes'];
        $candidates = $results['candidates'];

        $winnerName = '-';
        $winnerVotes = 0;
        $winnerPct = 0.0;
        if (!empty($candidates)) {
            $top = $candidates[0];
            $winnerName = $top['name'];
            $winnerVotes = (int)$top['votes_count'];
            $winnerPct = (float)$top['percentage'];
        }

        $pdf = new PdfReport('Pemilihan Ketua ICP Periode 2026-2027');
        $pdf->AddPage();
        $pdf->renderElectionSummary($totalVotes, $winnerName, $winnerVotes, $winnerPct);
        $pdf->renderCandidateTable($candidates, $totalVotes);
        $pdf->renderVoterAudit($voters);
        $pdf->renderSignatures();

        $filename = 'Laporan_Hasil_Pemilihan_ICP_2026-2027_' . date('Ymd_His') . '.pdf';
        $targetPath = $this->downloadDir . $filename;

        // Save into project download folder
        $pdfContent = $pdf->Output('S');
        file_put_contents($targetPath, $pdfContent);

        // Stream download to browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdfContent));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo $pdfContent;
        exit;
    }

    public function exportXlsx(): void
    {
        $results = Vote::getResults();
        $voters = Vote::getAllVoters();
        $totalVotes = $results['total_votes'];
        $candidates = $results['candidates'];

        $writer = new SimpleXLSXWriter();

        // 1. Sheet: Rekapitulasi Kandidat
        $candHeaders = ['No Urut', 'Nama Pasangan Calon', 'Tagline', 'Perolehan Suara', 'Persentase (%)'];
        $candRows = [];
        foreach ($candidates as $c) {
            $candRows[] = [
                '#' . sprintf('%02d', $c['candidate_number']),
                $c['name'],
                $c['tagline'] ?? '',
                (int)$c['votes_count'],
                number_format((float)$c['percentage'], 2) . '%'
            ];
        }
        $candRows[] = ['TOTAL', 'Total Suara Masuk', '', $totalVotes, '100.00%'];
        $writer->addSheet('Rekap Kandidat', $candHeaders, $candRows, [12, 35, 45, 18, 18]);

        // 2. Sheet: Data Pemilih (Audit Log)
        $voterHeaders = ['No', 'NIM', 'Nama Mahasiswa', 'Kandidat Pilihan', 'Waktu Voting', 'IP Address'];
        $voterRows = [];
        $idx = 1;
        foreach ($voters as $v) {
            $voterRows[] = [
                $idx++,
                $v['nim'],
                $v['voter_name'],
                '#' . sprintf('%02d', $v['candidate_number']) . ' ' . $v['candidate_name'],
                $v['voted_at'],
                $v['ip_address'] ?? '-'
            ];
        }
        $writer->addSheet('Daftar Pemilih', $voterHeaders, $voterRows, [8, 20, 30, 35, 22, 18]);

        $filename = 'Hasil_Voting_ICP_2026-2027_' . date('Ymd_His') . '.xlsx';
        $targetPath = $this->downloadDir . $filename;

        // Write to downloads folder
        $writer->writeToFile($targetPath);

        // Stream download to browser
        if (file_exists($targetPath)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($targetPath));
            header('Cache-Control: max-age=0');
            readfile($targetPath);
            exit;
        }

        echo "Gagal mengunduh file Excel.";
        exit;
    }
}
