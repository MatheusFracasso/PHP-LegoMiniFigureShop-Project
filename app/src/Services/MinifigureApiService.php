<?php

namespace App\Services;

class MinifigureApiService implements IMinifigureApiService
{
    private MinifigureService $minifigureService;

    public function __construct()
    {
        $this->minifigureService = new MinifigureService();
    }

    public function getMinifiguresForApi(): array
    {
        $minifigures = $this->minifigureService->getAllMinifigures();

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

        return $result;
    }
}
