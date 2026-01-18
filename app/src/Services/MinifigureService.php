<?php
namespace App\Services;
use App\Models\MiniFigure;
use App\Repositories\MiniFigureRepository;

class MinifigureService implements IMinifigureService
{
    private MiniFigureRepository $repository;

    public function __construct()
    {
        $this->repository = new MiniFigureRepository();
    }

    public function getAllMinifigures(): array
    {
        return $this->repository->getAll();
    }
   
    public function getById(int $id): ?Minifigure
    {
        return $this->repository->getById($id);
    }

    public function create(string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): int
    {
        return $this->repository->create($name, $priceCents, $categoryId, $imageUrl, $description);
    }

    public function update(int $id, string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): bool
    {
        return $this->repository->update($id, $name, $priceCents, $categoryId, $imageUrl, $description);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
