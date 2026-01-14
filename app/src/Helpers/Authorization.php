<?php

namespace App\Helpers;

class Authorization
{
    public static function requireAdmin(): void
    {
        $user = $_SESSION['user'] ?? null;

        if ($user === null || ($user['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}
