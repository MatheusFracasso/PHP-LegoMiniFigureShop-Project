<?php
namespace App\Services;
use App\Models\MiniFigure;
use App\Repositories\MiniFigureRepository;
class MinifigureService
{
    private MiniFigureRepository $repository;

    public function __construct()
    {
        $this->repository = new MiniFigureRepository();
    }

    /** @return MiniFigure[] */
    public function getAllMinifigures(): array
    {
        return $this->repository->getAll();
    }
   
    public function getById(int $id): ?Minifigure
    {
        return $this->repository->getById($id);
    }
}
