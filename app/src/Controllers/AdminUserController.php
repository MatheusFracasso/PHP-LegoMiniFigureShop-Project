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

        $pageTitle = "Admin - Users";
        $users = $this->repo->getAllUsers();

        $contentView = __DIR__ . '/../Views/admin/user/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function changeRole(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;
        $role = $_POST['role'] ?? 'user';
        $this->repo->updateRole($id, $role);
        header('Location: /admin/users');
        exit;
    }
}