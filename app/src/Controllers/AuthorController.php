<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

class AuthorController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    // GET /register
    public function showRegister(array $parameters = []): void
    {
        $pageTitle = 'Register';
        $contentView = __DIR__ . '/../Views/author/register.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /register
    public function register(array $parameters = []): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $password2 = trim($_POST['password2'] ?? '');

        if ($email === '' || $password === '' || $password2 === '') {
            $pageTitle = 'Register';
            $error = 'Please fill in all fields.';
            $contentView = __DIR__ . '/../Views/author/register.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        if ($password !== $password2) {
            $pageTitle = 'Register';
            $error = 'Passwords do not match.';
            $contentView = __DIR__ . '/../Views/author/register.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        $existing = $this->userRepo->findByEmail($email);
        if ($existing !== null) {
            $pageTitle = 'Register';
            $error = 'Email is already registered.';
            $contentView = __DIR__ . '/../Views/author/register.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepo->createUser($email, $passwordHash, 'user');

        // Log in right after registration
        $_SESSION['user'] = [
            'id' => $userId,
            'email' => $email,
            'role' => 'user'
        ];

        header('Location: /minifigures');
        exit;
    }

    // GET /login
    public function showLogin(array $parameters = []): void
    {
        $pageTitle = 'Login';
        $contentView = __DIR__ . '/../Views/author/login.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /login
    public function login(array $parameters = []): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $pageTitle = 'Login';
            $error = 'Please fill in email and password.';
            $contentView = __DIR__ . '/../Views/author/login.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        $user = $this->userRepo->findByEmail($email);
        if ($user === null) {
            $pageTitle = 'Login';
            $error = 'Invalid email or password.';
            $contentView = __DIR__ . '/../Views/author/login.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        $ok = password_verify($password, $user['passwordHash']);
        if ($ok === false) {
            $pageTitle = 'Login';
            $error = 'Invalid email or password.';
            $contentView = __DIR__ . '/../Views/author/login.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role']
        ];

        header('Location: /minifigures');
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
