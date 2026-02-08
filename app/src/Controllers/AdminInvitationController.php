<?php
namespace App\Controllers;

use App\Helpers\Authorization;
use App\Repositories\InvitationRepository;

class AdminInvitationController
{
    private InvitationRepository $repo;

    public function __construct()
    {
        $this->repo = new InvitationRepository();
    }

    public function index(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $pageTitle = "Admin - Invitations";
        $invitations = $this->repo->getAllInvitations();

        $contentView = __DIR__ . '/../Views/admin/invitation/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function create(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $pageTitle = "Admin - Send Invitation";

        $contentView = __DIR__ . '/../Views/admin/invitation/create.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function send(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $email = $_POST['email'] ?? '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email address';
            header('Location: /admin/invitations/create');
            exit;
        }

        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        $invitedBy = $_SESSION['user']['id'];
        $this->repo->createInvitation($email, $invitedBy, $token);

        $_SESSION['success'] = 'Invitation sent successfully';
        header('Location: /admin/invitations');
        exit;
    }
}
