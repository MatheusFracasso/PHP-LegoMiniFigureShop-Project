<?php

namespace App\Services;

use App\Models\MiniFigure;

// Minifigure business logic
interface IMinifigureService
{
    // Get all minifigures
    public function getAllMinifigures(): array;

    // Get one minifigure by ID
    public function getById(int $id): ?MiniFigure;

    // Create new minifigure
    public function create(string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): int;

    // Update existing minifigure
    public function update(int $id, string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): bool;

    // Delete minifigure
    public function delete(int $id): bool;
}
