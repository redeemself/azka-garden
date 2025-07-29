<?php
namespace App\User\ProductManagement;

class Review
{
    private int $id;
    private int $productId;
    private int $userId;
    private int $rating;
    private ?string $comment;
    private ?string $imageUrl;

    public function __construct(
        int $id,
        int $productId,
        int $userId,
        int $rating,
        ?string $comment = null,
        ?string $imageUrl = null
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->imageUrl = $imageUrl;
    }

    // Getters and setters here
}
