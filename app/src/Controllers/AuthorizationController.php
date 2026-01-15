<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthorizationController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    // GET /register
    public function showRegister(array $parameters = []): void
    {
        $pageTitle = 'Register';
        $contentView = __DIR__ . '/../Views/authorization/register.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /register
    public function register(array $parameters = []): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $name = $_POST['name'] ?? '';

        // Attempt registration via service
        $result = $this->authService->registerUser($email, $password, $name);

        // Check if registration was successful
        if (is_array($result)) {
            // Error case - result is ['error' => 'message']
            $pageTitle = 'Register';
            $error = $result['error'];
            $contentView = __DIR__ . '/../Views/authorization/register.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // Success case - result is the userId (int)
        $userId = $result;

        // Log in right after registration
        $_SESSION['user'] = [
            'id' => $userId,
            'email' => trim($email),
            'role' => 'user',
            'name' => trim($name)
        ];

        $returnUrl = $_SESSION['returnUrl'] ?? '/minifigures';
        unset($_SESSION['returnUrl']);

        header('Location: ' . $returnUrl);
        exit;
    }

    // GET /login
    public function showLogin(array $parameters = []): void
    {
        $pageTitle = 'Login';
        $contentView = __DIR__ . '/../Views/authorization/login.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /login
    public function login(array $parameters = []): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Attempt authentication via service
        $user = $this->authService->authenticate($email, $password);

        if ($user === null) {
            // Authentication failed
            $pageTitle = 'Login';
            $error = 'Invalid email or password.';
            $contentView = __DIR__ . '/../Views/authorization/login.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // Set session with authenticated user data
        $_SESSION['user'] = $user;

        $returnUrl = $_SESSION['returnUrl'] ?? '/minifigures';
        unset($_SESSION['returnUrl']);

        header('Location: ' . $returnUrl);
        exit;
    }

    // POST /logout
    public function logout(array $parameters = []): void
    {
        unset($_SESSION['user']);
        header('Location: /login');
        exit;
    }
}
