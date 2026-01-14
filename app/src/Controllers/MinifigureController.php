<?php
namespace App\Controllers;
use App\Models\MiniFigure;
use App\Repositories\MiniFigureRepository;
use App\Services\MinifigureService;

class MinifigureController
{
    private MinifigureService $service;

    public function __construct()
    {
        $this->service = new MinifigureService();
    }

    //GET minifigures (homepage)
    public function home(array $parameters = []): void
    {
        $pageTitle = "Lego Minifigure Store - Home";
        $contentView = __DIR__ . '/../Views/minifigure/home.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    //GET shop (list all minifigures)
    /** @return MiniFigure[] */
    public function index (array $parameters = []): void
    {
        $pageTitle = "Shop - All Mini Figures";
        $minifigures = $this->service->getAllMinifigures();

        $contentView = __DIR__ . '/../Views/minifigure/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
    
    //GET minifigures/{id}
    public function detail(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;
        $minifigure = $this->service->getById($id);

        if ($minifigure === null) {
            http_response_code(404);
            echo "Minifigure not found.";
            return;
        }

        $pageTitle = $minifigure->name;

        $contentView = __DIR__ . '/../Views/minifigure/detail.php';
        require __DIR__ . '/../Views/layout/main.php';
    }    
}