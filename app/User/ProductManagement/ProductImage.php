<?php
namespace App\User\ProductManagement;

class ProductImage
{
    private int $id;
    private int $productId;
    private string $imageUrl;
    private bool $isPrimary;

    public function __construct(int $id, int $productId, string $imageUrl, bool $isPrimary = false)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->imageUrl = $imageUrl;
        $this->isPrimary = $isPrimary;
    }

    // Getters and setters here
}
