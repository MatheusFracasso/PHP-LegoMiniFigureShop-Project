<?php

namespace App\Controllers;

use App\Helpers\Authorization;

class AdminController
{
    public function dashboard(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $pageTitle = "Admin Dashboard";

        $contentView = __DIR__ . '/../Views/admin/dashboard.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}
