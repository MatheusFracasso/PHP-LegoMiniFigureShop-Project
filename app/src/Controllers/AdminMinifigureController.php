<?php
namespace App\Controllers;

use App\Helpers\Authorization;
use App\Services\MinifigureService;

class AdminMinifigureController
{
    private MinifigureService $service;

    public function __construct()
    {
        $this->service = new MinifigureService();
    }

    public function index(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $pageTitle = "Admin - Minifigures";
        $minifigures = $this->service->getAllMinifigures();

        $contentView = __DIR__ . '/../Views/admin/minifigure/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function create(array $parameters = []): void
    {
        Authorization::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $priceCents = (int)($_POST['priceCents'] ?? 0);
            $categoryId = (int)($_POST['categoryId'] ?? 0);
            $imageUrl = $_POST['imageUrl'] ?? '';
            $description = $_POST['description'] ?? '';

            $this->service->create($name, $priceCents, $categoryId, $imageUrl, $description);
            header('Location: /admin/minifigures');
            exit;
        }

        $pageTitle = "Admin - Create Minifigure";

        $contentView = __DIR__ . '/../Views/admin/minifigure/create.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function edit(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;
        $minifigure = $this->service->getById($id);

        if ($minifigure === null) {
            http_response_code(404);
            echo "Minifigure not found.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $priceCents = (int)($_POST['priceCents'] ?? 0);
            $categoryId = (int)($_POST['categoryId'] ?? 0);
            $imageUrl = $_POST['imageUrl'] ?? '';
            $description = $_POST['description'] ?? '';

            $this->service->update($id, $name, $priceCents, $categoryId, $imageUrl, $description);
            header('Location: /admin/minifigures');
            exit;
        }

        $pageTitle = "Admin - Edit Minifigure";

        $contentView = __DIR__ . '/../Views/admin/minifigure/edit.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function delete(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->service->delete($id);
            header('Location: /admin/minifigures');
            exit;
        }

        // For GET, show confirmation or just delete, but for simplicity, redirect to index
        header('Location: /admin/minifigures');
        exit;
    }
}