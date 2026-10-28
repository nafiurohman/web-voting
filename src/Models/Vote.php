<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Vote
{
    public static function hasVoted(string $nim): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM voters WHERE LOWER(TRIM(nim)) = LOWER(TRIM(:nim)) LIMIT 1");
        $stmt->execute(['nim' => $nim]);
        return (bool)$stmt->fetch();
    }

    public static function castVote(string $voterName, string $nim, int $candidateId, ?string $ip = null, ?string $ua = null): array
    {
        $db = Database::getConnection();

        // 1. Validate Candidate exists
        $stmtCand = $db->prepare("SELECT id, name, candidate_number FROM candidates WHERE id = :id LIMIT 1");
        $stmtCand->execute(['id' => $candidateId]);
        $candidate = $stmtCand->fetch();
        if (!$candidate) {
            return ['success' => false, 'message' => 'Kandidat yang Anda pilih tidak valid.'];
        }

        // 2. Check duplicate voting by NIM
        $cleanNim = trim($nim);
        if (self::hasVoted($cleanNim)) {
            return [
                'success' => false,
                'message' => "NIM '{$cleanNim}' sudah tercatat pernah memberikan suara sebelumnya! 1 NIM hanya boleh memilih 1 kali."
            ];
        }

        try {
            $db->beginTransaction();

            // Insert voter with NIM
            $stmt = $db->prepare("
                INSERT INTO voters (nim, voter_name, candidate_id, ip_address, user_agent, voted_at)
                VALUES (:nim, :voter_name, :candidate_id, :ip_address, :user_agent, NOW())
            ");
            $stmt->execute([
                'nim' => $cleanNim,
                'voter_name' => trim($voterName),
                'candidate_id' => $candidateId,
                'ip_address' => $ip,
                'user_agent' => $ua ? substr($ua, 0, 500) : null
            ]);

            // Increment candidate count
            $stmtInc = $db->prepare("UPDATE candidates SET votes_count = votes_count + 1 WHERE id = :id");
            $stmtInc->execute(['id' => $candidateId]);

            $db->commit();

            return [
                'success' => true,
                'message' => "Suara Anda berhasil dicatat untuk Pasangan Kandidat #{$candidate['candidate_number']} ({$candidate['name']})!",
                'candidate' => $candidate
            ];
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                return [
                    'success' => false,
                    'message' => "NIM '{$cleanNim}' sudah pernah melakukan voting sebelumnya."
                ];
            }
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem saat mencatat suara: ' . $e->getMessage()];
        }
    }

    public static function getTotalVotes(): int
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT COUNT(*) as total FROM voters");
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    public static function getResults(): array
    {
        $db = Database::getConnection();
        $totalVotes = self::getTotalVotes();

        $stmt = $db->query("
            SELECT id, candidate_number, name, tagline, photo, votes_count
            FROM candidates
            ORDER BY votes_count DESC, candidate_number ASC
        ");
        $candidates = $stmt->fetchAll();

        $results = [];
        foreach ($candidates as $c) {
            $votes = (int)$c['votes_count'];
            $pct = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) : 0;
            $results[] = [
                'id' => (int)$c['id'],
                'candidate_number' => (int)$c['candidate_number'],
                'name' => $c['name'],
                'tagline' => $c['tagline'],
                'photo' => $c['photo'],
                'votes_count' => $votes,
                'percentage' => $pct
            ];
        }

        return [
            'total_votes' => $totalVotes,
            'candidates' => $results,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }

    public static function getTimelineBreakdown(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT DATE_FORMAT(voted_at, '%H:00') as time_slot, COUNT(*) as total_votes
            FROM voters
            GROUP BY time_slot
            ORDER BY time_slot ASC
        ");
        return $stmt->fetchAll();
    }

    public static function getRecentVoters(int $limit = 10): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT v.id, v.nim, v.voter_name, v.voted_at, c.candidate_number, c.name as candidate_name
            FROM voters v
            JOIN candidates c ON v.candidate_id = c.id
            ORDER BY v.voted_at DESC, v.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllVoters(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT v.id, v.nim, v.voter_name, v.voted_at, v.ip_address, c.candidate_number, c.name as candidate_name
            FROM voters v
            JOIN candidates c ON v.candidate_id = c.id
            ORDER BY v.voted_at DESC
        ");
        return $stmt->fetchAll();
    }

    public static function deleteVoter(int $voterId): bool
    {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            // Find candidate_id
            $stmt = $db->prepare("SELECT candidate_id FROM voters WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $voterId]);
            $row = $stmt->fetch();

            if ($row) {
                $candId = (int)$row['candidate_id'];

                // Delete voter
                $stmtDel = $db->prepare("DELETE FROM voters WHERE id = :id");
                $stmtDel->execute(['id' => $voterId]);

                // Decrement candidate votes
                $stmtDec = $db->prepare("UPDATE candidates SET votes_count = GREATEST(0, votes_count - 1) WHERE id = :cid");
                $stmtDec->execute(['cid' => $candId]);
            }

            $db->commit();
            return true;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public static function resetAllVotes(): bool
    {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();
            $db->exec("DELETE FROM voters");
            $db->exec("UPDATE candidates SET votes_count = 0");
            $db->commit();
            return true;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }
}
