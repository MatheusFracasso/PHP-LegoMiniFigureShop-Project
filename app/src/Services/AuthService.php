<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AuthService implements IAuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function validateRegistration(string $email, string $password, string $password2, string $name): ?string
    {
        $email = trim($email);
        $password = trim($password);
        $password2 = trim($password2);
        $name = trim($name);

        if ($email === '' || $password === '' || $password2 === '' || $name === '') {
            return 'Please fill in all fields.';
        }

        if ($password !== $password2) {
            return 'Passwords do not match.';
        }

        $existing = $this->userRepository->findByEmail($email);
        if ($existing !== null) {
            return 'Email is already registered.';
        }

        return null;
    }

    public function registerUser(string $email, string $password, string $name): int|array
    {
        $error = $this->validateRegistration($email, $password, $password, $name);
        if ($error !== null) {
            return ['error' => $error];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepository->createUser($email, $passwordHash, 'user', $name);

        return $userId;
    }

    public function authenticate(string $email, string $password): ?array
    {
        $email = trim($email);
        $password = trim($password);

        if ($email === '' || $password === '') {
            return null;
        }

        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            return null;
        }

        if (!password_verify($password, $user['passwordHash'])) {
            return null;
        }

        return [
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role'],
            'name' => (string)$user['name']
        ];
    }
}
