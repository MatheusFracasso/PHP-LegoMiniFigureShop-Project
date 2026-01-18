<?php

namespace App\Repositories;

use App\Models\MiniFigure;

// Interface for minifigure database operations
interface IMiniFigureRepository
{
    // Returns all minifigures from DB
    public function getAll(): array;

    // Get one minifigure by ID, or null if not found
    public function getById(int $id): ?MiniFigure;

    // Create new minifigure, returns the new ID
    public function create(string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): int;

    // Update existing minifigure by ID
    public function update(int $id, string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): bool;

    // Delete minifigure from database
    public function delete(int $id): bool;
}
