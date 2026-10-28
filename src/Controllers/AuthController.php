<?php
namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function loginForm(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require __DIR__ . '/../../public/views/login.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['flash_error'] = 'Silakan masukkan username dan password.';
            header('Location: /login');
            exit;
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['flash_success'] = 'Selamat datang, ' . htmlspecialchars($user['name']) . '!';
            header('Location: /admin/dashboard');
            exit;
        }

        $_SESSION['flash_error'] = 'Username atau password tidak cocok! (Default: icp / icp123)';
        header('Location: /login');
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}
