<?php

namespace App\Repositories;

// Invitation database operations
interface IInvitationRepository
{
    // Create new invitation, returns invitation ID
    public function createInvitation(string $email, int $invitedBy, string $token): int;

    // Get all invitations
    public function getAllInvitations(): array;

    // Get invitations by email
    public function getInvitationsByEmail(string $email): array;

    // Get invitation by token
    public function getInvitationByToken(string $token): ?array;

    // Update invitation status
    public function updateStatus(int $id, string $status): bool;

    // Get invitations sent by a specific user
    public function getInvitationsByInviter(int $invitedBy): array;
}
