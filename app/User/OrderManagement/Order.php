<?php
namespace App\User\OrderManagement;

class Order
{
    private int $id;
    private int $userId;
    private string $orderCode;
    private \DateTime $orderDate;
    private int $orderStatusId;
    private float $totalPrice;
    private float $shippingCost;
    private ?string $note;

    public function __construct(
        int $id,
        int $userId,
        string $orderCode,
        \DateTime $orderDate,
        int $orderStatusId,
        float $totalPrice,
        float $shippingCost,
        ?string $note = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderCode = $orderCode;
        $this->orderDate = $orderDate;
        $this->orderStatusId = $orderStatusId;
        $this->totalPrice = $totalPrice;
        $this->shippingCost = $shippingCost;
        $this->note = $note;
    }

    // Getters and setters here
}
