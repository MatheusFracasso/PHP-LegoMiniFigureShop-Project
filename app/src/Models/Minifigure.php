<?php
namespace App\Models;

class Minifigure
{
    public int $id;
    public string $name;
    public int $priceCents;
    public string $category;
    public string $imageUrl;
    public string $description;
    public ?int $stock;
    public ?string $origin;
    public ?string $categoryName; 

    public function __construct(int $id, string $name, int $priceCents, string $category, string $imageUrl, string $description, ?int $stock=null, ?string $origin=null, ?string $categoryName=null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priceCents = $priceCents;
        $this->category = $category;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
        $this->stock = $stock;
        $this->origin = $origin;
        $this->categoryName = $categoryName;
    }

    public function priceEuro():string
    {
        $euros = $this->priceCents / 100;
        return '€' . number_format($euros, 2, '.', '');
    }
}