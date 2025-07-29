<?php
namespace App\User\ProductManagement;

class Product
{
    private int $id;
    private int $categoryId;
    private string $name;
    private ?string $description;
    private int $stock;
    private float $price;
    private float $weight;
    private ?string $imageUrl;
    private bool $status;

    public function __construct(
        int $id,
        int $categoryId,
        string $name,
        ?string $description,
        int $stock,
        float $price,
        float $weight,
        ?string $imageUrl = null,
        bool $status = true
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->stock = $stock;
        $this->price = $price;
        $this->weight = $weight;
        $this->imageUrl = $imageUrl;
        $this->status = $status;
    }

    // Getters and setters here
}
