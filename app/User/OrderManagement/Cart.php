<?php
namespace App\User\OrderManagement;

class Cart
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $quantity;
    private ?string $note;

    public function __construct(
        int $id,
        int $userId,
        int $productId,
        int $quantity,
        ?string $note = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->note = $note;
    }

    // Getters and setters here
}
