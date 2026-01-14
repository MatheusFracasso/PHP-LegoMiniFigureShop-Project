<?php
namespace App\Controllers;

use App\Helpers\Authorization;
use App\Repositories\UserRepository;

class AdminUserController
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function index(array $parameters = []): void
    {
        Authorization::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['userId'] ?? 0);
            $role = $_POST['role'] ?? 'user';
            $this->repo->updateRole($userId, $role);
            header('Location: /admin/users');
            exit;
        }

        $pageTitle = "Admin - Users";
        $users = $this->repo->getAllUsers();

        $contentView = __DIR__ . '/../Views/admin/user/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}