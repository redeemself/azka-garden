<?php
namespace App\User\OrderManagement;

class OrderDetail
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;
    private float $price;
    private float $subtotal;
    private ?string $note;

    public function __construct(
        int $id,
        int $orderId,
        int $productId,
        int $quantity,
        float $price,
        float $subtotal,
        ?string $note = null
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->subtotal = $subtotal;
        $this->note = $note;
    }

    // Getters and setters here
}
