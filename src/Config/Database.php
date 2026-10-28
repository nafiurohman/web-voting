<?php
namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'db';
            $port = getenv('DB_PORT') ?: '3306';
            $dbname = getenv('DB_NAME') ?: 'icp_voting';
            $user = getenv('DB_USER') ?: 'icp_user';
            $password = getenv('DB_PASS') ?: 'icp_password';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // Re-try connection a few times if database is still starting up
            $attempts = 0;
            $maxAttempts = 10;
            while ($attempts < $maxAttempts) {
                try {
                    self::$instance = new PDO($dsn, $user, $password, $options);
                    break;
                } catch (PDOException $e) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        throw new PDOException("Database connection failed after {$maxAttempts} attempts: " . $e->getMessage());
                    }
                    usleep(1000000); // Wait 1 second before retrying
                }
            }
        }

        return self::$instance;
    }
}
