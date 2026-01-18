<?php

namespace App\Services;

// Authentication and user registration logic
interface IAuthService
{
    // Validate registration input, returns error message or null if valid
    public function validateRegistration(string $email, string $password, string $password2, string $name): ?string;

    // Register new user, returns user ID or error array
    public function registerUser(string $email, string $password, string $name): int|array;

    // Check login credentials, returns user data or null
    public function authenticate(string $email, string $password): ?array;
}
