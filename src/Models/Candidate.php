<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Candidate
{
    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM candidates ORDER BY candidate_number ASC");
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM candidates WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO candidates (candidate_number, name, tagline, photo, vision, mission, programs, votes_count)
            VALUES (:candidate_number, :name, :tagline, :photo, :vision, :mission, :programs, 0)
        ");
        $stmt->execute([
            'candidate_number' => (int)$data['candidate_number'],
            'name' => trim($data['name']),
            'tagline' => trim($data['tagline'] ?? ''),
            'photo' => trim($data['photo'] ?? '/assets/uploads/default.svg'),
            'vision' => trim($data['vision']),
            'mission' => trim($data['mission']),
            'programs' => trim($data['programs'] ?? '')
        ]);
        return (int)$db->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE candidates 
            SET candidate_number = :candidate_number,
                name = :name,
                tagline = :tagline,
                photo = :photo,
                vision = :vision,
                mission = :mission,
                programs = :programs
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'candidate_number' => (int)$data['candidate_number'],
            'name' => trim($data['name']),
            'tagline' => trim($data['tagline'] ?? ''),
            'photo' => trim($data['photo'] ?? '/assets/uploads/default.svg'),
            'vision' => trim($data['vision']),
            'mission' => trim($data['mission']),
            'programs' => trim($data['programs'] ?? '')
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM candidates WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function getNextNumber(): int
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT MAX(candidate_number) as max_num FROM candidates");
        $row = $stmt->fetch();
        return ((int)($row['max_num'] ?? 0)) + 1;
    }
}
