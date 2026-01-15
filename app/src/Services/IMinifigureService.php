<?php

namespace App\Services;

use App\Models\MiniFigure;

interface IMinifigureService
{
    /**
     * Get all minifigures.
     *
     * @return MiniFigure[] Array of minifigures
     */
    public function getAllMinifigures(): array;

    /**
     * Get a single minifigure by ID.
     *
     * @param int $id The minifigure ID
     * @return MiniFigure|null The minifigure or null if not found
     */
    public function getById(int $id): ?MiniFigure;

    /**
     * Create a new minifigure.
     *
     * @param string $name The minifigure name
     * @param int $priceCents Price in cents
     * @param int $categoryId Category ID
     * @param string $imageUrl URL to the minifigure image
     * @param string $description Minifigure description
     * @return int The ID of the newly created minifigure
     */
    public function create(string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): int;

    /**
     * Update an existing minifigure.
     *
     * @param int $id The minifigure ID
     * @param string $name The new name
     * @param int $priceCents New price in cents
     * @param int $categoryId New category ID
     * @param string $imageUrl New image URL
     * @param string $description New description
     * @return bool True if update was successful, false otherwise
     */
    public function update(int $id, string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): bool;

    /**
     * Delete a minifigure.
     *
     * @param int $id The minifigure ID
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool;
}
