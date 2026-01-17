<?php

namespace App\Services;

interface IAuthService
{
    /**
     * Validate registration input (checks empty fields, password match, duplicate email).
     *
     * @return string|null Error message if invalid, null if valid
     */
    public function validateRegistration(string $email, string $password, string $password2, string $name): ?string;

    /**
     * Register a new user. Returns user ID on success or error array on failure.
     *
     * @return int|array User ID or ['error' => 'message']
     */
    public function registerUser(string $email, string $password, string $name): int|array;

    /**
     * Authenticate user. Returns user data on success, null on failure.
     *
     * @return array|null User data (without password) or null if invalid
     */
    public function authenticate(string $email, string $password): ?array;
}
