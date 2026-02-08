<?php
namespace App\Controllers;

use App\Helpers\Authorization;
use App\Repositories\InvitationRepository;

class UserInvitationController
{
    private InvitationRepository $repo;

    public function __construct()
    {
        $this->repo = new InvitationRepository();
    }

    public function myInvitations(array $parameters = []): void
    {
        Authorization::requireLogin();

        $pageTitle = "My Invitations";
        $email = $_SESSION['user']['email'];
        $invitations = $this->repo->getInvitationsByEmail($email);

        $contentView = __DIR__ . '/../Views/user/invitations.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}
