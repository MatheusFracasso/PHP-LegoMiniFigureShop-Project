<?php
namespace App\Models;

class MiniFigure
{
    public int $id;
    public string $name;
    public int $priceCents;
    public string $category;
    public string $imageUrl;
    public string $description;

    public function __construct(int $id, string $name, int $priceCents, string $category, string $imageUrl, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priceCents = $priceCents;
        $this->category = $category;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
    }

    public function getPriceEuro():string
    {
        $euros = $this->priceCents / 100;
        return number_format($euros, 2, '.', '');
    }
}