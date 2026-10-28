<?php
namespace App\Controllers;

use App\Models\Candidate;
use App\Models\Vote;

class DashboardController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Dashboard quick count dan hasil pemilihan hanya dapat diakses oleh Panitia/Admin yang sudah login.';
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        $results = Vote::getResults();
        $timelineStats = Vote::getTimelineBreakdown();
        $recentVoters = Vote::getRecentVoters(8);
        $totalVotes = $results['total_votes'];
        $candidates = $results['candidates'];

        require __DIR__ . '/../../public/views/live_count.php';
    }

    public function apiResults(): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $results = Vote::getResults();
        $timelineStats = Vote::getTimelineBreakdown();
        $recentVoters = Vote::getRecentVoters(10);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'total_votes' => $results['total_votes'],
                'candidates' => $results['candidates'],
                'timeline_stats' => $timelineStats,
                'recent_voters' => $recentVoters,
                'timestamp' => date('H:i:s d/m/Y')
            ]
        ]);
    }

    public function apiStream(): void
    {
        // Server-Sent Events (SSE) for Realtime Updates
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        // Disable output buffering
        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        $lastHash = '';
        $startTime = time();

        // Stream for up to 30 seconds per connection, browser reconnects automatically
        while ((time() - $startTime) < 30) {
            if (connection_aborted()) {
                break;
            }

            $results = Vote::getResults();
            $timelineStats = Vote::getTimelineBreakdown();
            $recentVoters = Vote::getRecentVoters(8);

            $payload = [
                'total_votes' => $results['total_votes'],
                'candidates' => $results['candidates'],
                'timeline_stats' => $timelineStats,
                'recent_voters' => $recentVoters,
                'timestamp' => date('H:i:s')
            ];

            $currentHash = md5(json_encode($payload));
            if ($currentHash !== $lastHash) {
                echo "event: vote_update\n";
                echo "data: " . json_encode($payload) . "\n\n";
                flush();
                $lastHash = $currentHash;
            } else {
                // Heartbeat to keep connection alive
                echo ": heartbeat\n\n";
                flush();
            }

            sleep(1);
        }
        exit;
    }
}
