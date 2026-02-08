<?php

namespace App\Repositories;

use App\Config;
use PDO;

class InvitationRepository implements IInvitationRepository
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

    public function createInvitation(string $email, int $invitedBy, string $token): int
    {
        $sql = 'INSERT INTO invitations (email, invitedBy, token) VALUES (:email, :invitedBy, :token)';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'invitedBy' => $invitedBy,
            'token' => $token
        ]);

        return (int)$this->connection->lastInsertId();
    }

    public function getAllInvitations(): array
    {
        $sql = 'SELECT i.id, i.email, i.status, i.token, i.createdAt, i.acceptedAt, u.name AS invitedByName, u.email AS invitedByEmail
                FROM invitations i
                JOIN users u ON i.invitedBy = u.id
                ORDER BY i.createdAt DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvitationsByEmail(string $email): array
    {
        $sql = 'SELECT i.id, i.email, i.status, i.token, i.createdAt, i.acceptedAt, u.name AS invitedByName, u.email AS invitedByEmail
                FROM invitations i
                JOIN users u ON i.invitedBy = u.id
                WHERE i.email = :email
                ORDER BY i.createdAt DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvitationByToken(string $token): ?array
    {
        $sql = 'SELECT i.id, i.email, i.status, i.token, i.createdAt, i.invitedBy, u.name AS invitedByName
                FROM invitations i
                JOIN users u ON i.invitedBy = u.id
                WHERE i.token = :token LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $row;
    }

    public function updateStatus(int $id, string $status): bool
    {
        if ($status === 'accepted') {
            $sql = 'UPDATE invitations SET status = :status, acceptedAt = NOW() WHERE id = :id';
        } elseif ($status === 'rejected') {
            $sql = 'UPDATE invitations SET status = :status, acceptedAt = NULL WHERE id = :id';
        } else {
            $sql = 'UPDATE invitations SET status = :status WHERE id = :id';
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }

    public function getInvitationsByInviter(int $invitedBy): array
    {
        $sql = 'SELECT i.id, i.email, i.status, i.token, i.createdAt, i.acceptedAt
                FROM invitations i
                WHERE i.invitedBy = :invitedBy
                ORDER BY i.createdAt DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['invitedBy' => $invitedBy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
