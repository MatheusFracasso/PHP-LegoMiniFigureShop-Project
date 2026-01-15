<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    /**
     * Find a user by email address.
     *
     * @param string $email The email to search for
     * @return array|null User data or null if not found
     */
    public function findByEmail(string $email): ?array;

    /**
     * Create a new user in the database.
     *
     * @param string $email User email (unique)
     * @param string $passwordHash Hashed password
     * @param string $role User role (user or admin)
     * @param string $name Full name
     * @return int The ID of the newly created user
     */
    public function createUser(string $email, string $passwordHash, string $role = 'user', string $name = ''): int;

    /**
     * Find a user by ID.
     *
     * @param int $id The user ID
     * @return array|null User data or null if not found
     */
    public function findById(int $id): ?array;

    /**
     * Get all users.
     *
     * @return array List of all users
     */
    public function getAllUsers(): array;

    /**
     * Update a user's role.
     *
     * @param int $id The user ID
     * @param string $role New role (user or admin)
     * @return bool True if update was successful, false otherwise
     */
    public function updateRole(int $id, string $role): bool;
}
