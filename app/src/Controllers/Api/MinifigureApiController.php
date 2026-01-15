<?php

namespace App\Controllers\Api;

use App\Services\MinifigureApiService;

class MinifigureApiController
{
    private MinifigureApiService $apiService;

    public function __construct()
    {
        $this->apiService = new MinifigureApiService();
    }

    public function index(array $parameters = []): void
    {
        $result = $this->apiService->getMinifiguresForApi();

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
