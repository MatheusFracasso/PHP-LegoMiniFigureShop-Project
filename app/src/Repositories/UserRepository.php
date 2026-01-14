<?php

namespace App\Repositories;

use App\Config;
use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $dsn = 'mysql:host=' . Config::DB_SERVER_NAME .
               ';dbname=' . Config::DB_NAME .
               ';charset=utf8mb4';

        $this->connection = new PDO($dsn, Config::DB_USERNAME, Config::DB_PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT id, email, passwordHash, role, name FROM users WHERE email = :email LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $row;
    }

    public function createUser(string $email, string $passwordHash, string $role = 'user', string $name = ''): int
    {
        $sql = 'INSERT INTO users (email, passwordHash, role, name) VALUES (:email, :passwordHash, :role, :name)';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'passwordHash' => $passwordHash,
            'role' => $role,
            'name' => $name
        ]);

        return (int)$this->connection->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, email, role, name FROM users WHERE id = :id LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $row;
    }

    public function getAllUsers(): array
    {
        $sql = 'SELECT id, email, role, name FROM users ORDER BY email';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRole(int $id, string $role): bool
    {
        $sql = 'UPDATE users SET role = :role WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id, 'role' => $role]);
        return $stmt->rowCount() > 0;
    }
}
