<?php
namespace App\Utils;

class PdfReport extends FPDF
{
    private string $electionTitle;
    private string $generatedAt;

    public function __construct(string $title = 'Pemilihan Ketua ICP Periode 2026-2027')
    {
        parent::__construct('P', 'mm', 'A4');
        $this->electionTitle = $title;
        $this->generatedAt = date('d F Y, H:i:s') . ' WIB';
        $this->SetAutoPageBreak(true, 25);
    }

    public function Header(): void
    {
        // Dark Blue Header Bar
        $this->SetFillColor(30, 58, 138); // Deep Blue
        $this->Rect(0, 0, 210, 28, 'F');

        // Gold Accent Line
        $this->SetFillColor(245, 158, 11); // Amber
        $this->Rect(0, 28, 210, 2.5, 'F');

        $this->SetY(6);
        $this->SetFont('helvetica', 'B', 15);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 7, 'INTERNATIONAL CLASS PROGRAM (ICP)', 0, 1, 'C');

        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(224, 231, 255);
        $this->Cell(0, 5, 'BERITA ACARA & REKAPITULASI RESMI HASIL PEMILIHAN KETUA', 0, 1, 'C');
        $this->Cell(0, 5, 'PERIODE 2026 - 2027', 0, 1, 'C');

        $this->SetY(34);
    }

    public function Footer(): void
    {
        $this->SetY(-18);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 4, 'Dokumen resmi di-generate secara otomatis oleh Sistem E-Voting Realtime ICP', 0, 1, 'C');
        $this->Cell(0, 4, 'Waktu Cetak: ' . $this->generatedAt . '  |  Halaman ' . $this->PageNo(), 0, 0, 'C');
    }

    public function renderElectionSummary(int $totalVotes, string $winnerName, int $winnerVotes, float $winnerPct): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(0, 7, 'I. RINGKASAN EKSEKUTIF PEMILIHAN', 0, 1, 'L');

        $this->SetFont('helvetica', '', 9.5);
        $this->SetTextColor(51, 65, 85);
        $this->MultiCell(0, 5, "Pada hari ini, panitia pemilihan menyatakan proses pemungutan suara elektronik (e-voting) untuk Pemilihan Ketua ICP Periode 2026-2027 telah terselenggara dengan transparan, tertib, dan akuntabel. Berikut adalah ikhtisar perolehan suara:");

        $this->Ln(3);

        // Summary Boxes
        $startX = 15;
        $boxW = 58;
        $boxH = 20;

        // Box 1: Total Suara
        $this->SetFillColor(241, 245, 249);
        $this->SetDrawColor(203, 213, 225);
        $this->Rect($startX, $this->GetY(), $boxW, $boxH, 'DF');
        $this->SetXY($startX, $this->GetY() + 2);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->Cell($boxW, 4, 'TOTAL SUARA MASUK', 0, 1, 'C');
        $this->SetX($startX);
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(30, 58, 138);
        $this->Cell($boxW, 8, number_format($totalVotes, 0, ',', '.') . ' Suara', 0, 1, 'C');

        // Box 2: Pemenang / Unggul
        $this->SetXY($startX + $boxW + 3, $this->GetY() - 14);
        $this->Rect($startX + $boxW + 3, $this->GetY(), $boxW, $boxH, 'DF');
        $this->SetXY($startX + $boxW + 3, $this->GetY() + 2);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->Cell($boxW, 4, 'KANDIDAT TERPILIH / UNGGUL', 0, 1, 'C');
        $this->SetX($startX + $boxW + 3);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetTextColor(4, 120, 87);
        $shortName = strlen($winnerName) > 22 ? substr($winnerName, 0, 20) . '...' : $winnerName;
        $this->Cell($boxW, 8, $shortName ?: '-', 0, 1, 'C');

        // Box 3: Persentase Pemenang
        $this->SetXY($startX + ($boxW * 2) + 6, $this->GetY() - 14);
        $this->Rect($startX + ($boxW * 2) + 6, $this->GetY(), $boxW, $boxH, 'DF');
        $this->SetXY($startX + ($boxW * 2) + 6, $this->GetY() + 2);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->Cell($boxW, 4, 'PERSENTASE PEROLEHAN', 0, 1, 'C');
        $this->SetX($startX + ($boxW * 2) + 6);
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(217, 119, 6);
        $this->Cell($boxW, 8, number_format($winnerPct, 1, ',', '.') . '%', 0, 1, 'C');

        $this->SetY($this->GetY() + 8);
    }

    public function renderCandidateTable(array $candidates, int $totalVotes): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(0, 7, 'II. PEROLEHAN SUARA KANDIDAT KETUA ICP', 0, 1, 'L');

        // Table Header
        $this->SetFillColor(30, 58, 138);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(203, 213, 225);
        $this->SetFont('helvetica', 'B', 9);

        $this->Cell(12, 8, 'No', 1, 0, 'C', true);
        $this->Cell(16, 8, 'Nomor', 1, 0, 'C', true);
        $this->Cell(85, 8, 'Nama Calon Ketua & Wakil', 1, 0, 'L', true);
        $this->Cell(35, 8, 'Perolehan Suara', 1, 0, 'C', true);
        $this->Cell(32, 8, 'Persentase (%)', 1, 1, 'C', true);

        // Table Body
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(30, 41, 59);

        $rank = 1;
        foreach ($candidates as $c) {
            $votes = (int)$c['votes_count'];
            $pct = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;
            $fill = ($rank % 2 == 0);
            $this->SetFillColor(248, 250, 252);

            $this->Cell(12, 7, (string)$rank, 1, 0, 'C', $fill);
            $this->Cell(16, 7, '#' . sprintf('%02d', $c['candidate_number']), 1, 0, 'C', $fill);
            $this->Cell(85, 7, ' ' . $c['name'], 1, 0, 'L', $fill);
            $this->Cell(35, 7, number_format($votes, 0, ',', '.') . ' suara', 1, 0, 'C', $fill);
            $this->Cell(32, 7, number_format($pct, 1, ',', '.') . '%', 1, 1, 'C', $fill);
            $rank++;
        }

        // Total Row
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(226, 232, 240);
        $this->Cell(113, 7, 'TOTAL SUARA SAH MASUK', 1, 0, 'R', true);
        $this->Cell(35, 7, number_format($totalVotes, 0, ',', '.') . ' suara', 1, 0, 'C', true);
        $this->Cell(32, 7, '100.0%', 1, 1, 'C', true);

        $this->Ln(6);
    }

    public function renderVoterAudit(array $voters): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(0, 7, 'III. AUDIT LOG PARTISIPASI MAHASISWA PEMILIH', 0, 1, 'L');

        $this->SetFillColor(51, 65, 85);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(203, 213, 225);
        $this->SetFont('helvetica', 'B', 9);

        $this->Cell(12, 8, 'No', 1, 0, 'C', true);
        $this->Cell(38, 8, 'NIM', 1, 0, 'C', true);
        $this->Cell(65, 8, 'Nama Mahasiswa', 1, 0, 'L', true);
        $this->Cell(35, 8, 'Pilihan', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Waktu', 1, 1, 'C', true);

        $this->SetFont('helvetica', '', 8.5);
        $this->SetTextColor(30, 41, 59);

        $limit = min(count($voters), 15); // Show up to 15 recent voters in PDF audit summary
        for ($i = 0; $i < $limit; $i++) {
            $v = $voters[$i];
            $fill = ($i % 2 == 1);
            $this->SetFillColor(248, 250, 252);

            $this->Cell(12, 6.5, (string)($i + 1), 1, 0, 'C', $fill);
            $this->Cell(38, 6.5, $v['nim'], 1, 0, 'C', $fill);
            $this->Cell(65, 6.5, ' ' . (strlen($v['voter_name']) > 28 ? substr($v['voter_name'], 0, 26) . '...' : $v['voter_name']), 1, 0, 'L', $fill);
            $this->Cell(35, 6.5, '#' . sprintf('%02d', $v['candidate_number']), 1, 0, 'C', $fill);
            $this->Cell(30, 6.5, substr($v['voted_at'], 11, 8), 1, 1, 'C', $fill);
        }

        if (empty($voters)) {
            $this->Cell(180, 7, 'Belum ada data pemilih yang masuk.', 1, 1, 'C');
        } elseif (count($voters) > $limit) {
            $remaining = count($voters) - $limit;
            $this->SetFont('helvetica', 'I', 8);
            $this->SetTextColor(100, 116, 139);
            $this->Cell(180, 5, "... dan {$remaining} pemilih lainnya tercatat lengkap di lampiran Excel (XLSX).", 0, 1, 'L');
        }

        $this->Ln(6);
    }

    public function renderSignatures(): void
    {
        if ($this->GetY() > 220) {
            $this->AddPage();
        }

        $this->SetFont('helvetica', 'B', 10);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(0, 6, 'PENGESAHAN HASIL PEMILIHAN', 0, 1, 'L');

        $this->SetFont('helvetica', '', 9);
        $this->MultiCell(0, 4, "Berita acara ini ditandatangani oleh Panitia Pemilihan Ketua ICP Periode 2026-2027 sebagai bukti sah dan berkekuatan penuh atas hasil rekapitulasi voting.");

        $this->Ln(5);

        $ySig = $this->GetY();
        $this->SetXY(20, $ySig);
        $this->Cell(70, 5, 'Ketua Panitia Pemilihan ICP,', 0, 0, 'C');
        $this->SetXY(120, $ySig);
        $this->Cell(70, 5, 'Saksi Mahasiswa & Dewan Pembina,', 0, 1, 'C');

        $this->Ln(18);

        $this->SetX(20);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(70, 5, '( Panitia Pemilihan Ketua ICP )', 0, 0, 'C');
        $this->SetX(120);
        $this->Cell(70, 5, '( Dewan Pengawas / Saksi )', 0, 1, 'C');

        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->SetX(20);
        $this->Cell(70, 4, 'NIM. 2026-ICP-COMM', 0, 0, 'C');
        $this->SetX(120);
        $this->Cell(70, 4, 'NIM. 2026-ICP-SAKSI', 0, 1, 'C');
    }
}
