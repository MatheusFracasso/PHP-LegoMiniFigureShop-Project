<?php

namespace App\Repositories;

// User database operations
interface IUserRepository
{
    // Find user by email
    public function findByEmail(string $email): ?array;

    // Create new user, returns user ID
    public function createUser(string $email, string $passwordHash, string $role = 'user', string $name = ''): int;

    // Find user by ID
    public function findById(int $id): ?array;

    // Get all users
    public function getAllUsers(): array;

    // Update user's role (user or admin)
    public function updateRole(int $id, string $role): bool;
}
