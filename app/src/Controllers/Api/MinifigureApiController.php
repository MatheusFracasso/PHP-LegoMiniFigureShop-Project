<?php
namespace App\Controllers\Api;

use App\Services\MinifigureService;

class MinifigureApiController
{
    private MinifigureService $service;

    public function __construct()
    {
        $this->service = new MinifigureService();
    }

    public function index(array $parameters = []): void
    {
        $minifigures = $this->service->getAllMinifigures();

        $result = [];
        foreach ($minifigures as $fig) {
            $result[] = [
                'id' => $fig->id,
                'name' => $fig->name,
                'priceCents' => $fig->priceCents,
                'priceEuro' => $fig->priceEuro(),
                'category' => $fig->category,
                'imageUrl' => str_replace('\\', '/', $fig->imageUrl),
                'description' => $fig->description
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}