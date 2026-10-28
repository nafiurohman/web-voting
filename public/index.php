<?php
/**
 * Front Controller & Router for Pemilihan Ketua ICP Periode 2026-2027
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2. Parse URI and Method
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Clean path
$path = rtrim($requestUri, '/');
if (empty($path)) {
    $path = '/';
}

// 3. Routing Table
try {
    switch ($path) {
        case '/':
            header('Location: /voting');
            exit;

        case '/voting':
            (new App\Controllers\VotingController())->index();
            break;

        case '/api/check-voter':
            if ($requestMethod === 'POST') {
                (new App\Controllers\VotingController())->checkVoter();
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
            }
            break;

        case '/api/vote':
            if ($requestMethod === 'POST') {
                (new App\Controllers\VotingController())->submitVote();
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
            }
            break;

        case '/dashboard':
            (new App\Controllers\DashboardController())->index();
            break;

        case '/api/results':
            (new App\Controllers\DashboardController())->apiResults();
            break;

        case '/api/stream':
            (new App\Controllers\DashboardController())->apiStream();
            break;

        case '/login':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AuthController())->login();
            } else {
                (new App\Controllers\AuthController())->loginForm();
            }
            break;

        case '/logout':
            (new App\Controllers\AuthController())->logout();
            break;

        case '/admin':
        case '/admin/dashboard':
            (new App\Controllers\AdminController())->dashboard();
            break;

        case '/admin/candidates':
            (new App\Controllers\AdminController())->candidates();
            break;

        case '/admin/candidates/create':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->saveCandidate();
            } else {
                (new App\Controllers\AdminController())->candidateCreate();
            }
            break;

        case '/admin/candidates/edit':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->saveCandidate();
            } else {
                (new App\Controllers\AdminController())->candidateEdit();
            }
            break;

        case '/admin/candidates/delete':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->deleteCandidate();
            } else {
                header('Location: /admin/candidates');
            }
            break;

        case '/admin/voters':
            (new App\Controllers\AdminController())->voters();
            break;

        case '/admin/voters/delete':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->deleteVoter();
            } else {
                header('Location: /admin/voters');
            }
            break;

        case '/admin/voters/clear':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->clearAllVoters();
            } else {
                header('Location: /admin/voters');
            }
            break;

        case '/admin/reset':
            if ($requestMethod === 'POST') {
                (new App\Controllers\AdminController())->resetElection();
            } else {
                header('Location: /admin/dashboard');
            }
            break;

        case '/export/pdf':
            (new App\Controllers\ExportController())->exportPdf();
            break;

        case '/export/xlsx':
            (new App\Controllers\ExportController())->exportXlsx();
            break;

        default:
            http_response_code(404);
            require __DIR__ . '/views/404.php';
            break;
    }
} catch (\Throwable $e) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Sistem Pemilihan ICP - Terjadi Kesalahan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-lg w-full bg-slate-800 rounded-2xl p-8 border border-slate-700 text-center shadow-2xl">
            <div class="w-16 h-16 bg-red-500/20 text-red-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">!</div>
            <h1 class="text-xl font-bold text-white mb-2">Terjadi Kesalahan Server</h1>
            <p class="text-slate-400 text-sm mb-4"><?= htmlspecialchars($e->getMessage()) ?></p>
            <a href="/" class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
}
