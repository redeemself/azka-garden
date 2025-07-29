<?php
namespace App\User\ShippingManagement;

class Shipping
{
    private int $id;
    private int $orderId;
    private string $courier;
    private string $service;
    private ?string $trackingNumber;
    private float $shippingCost;
    private string $status;
    private ?\DateTime $estimatedDelivery;

    public function __construct(
        int $id,
        int $orderId,
        string $courier,
        string $service,
        ?string $trackingNumber,
        float $shippingCost,
        string $status,
        ?\DateTime $estimatedDelivery = null
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->courier = $courier;
        $this->service = $service;
        $this->trackingNumber = $trackingNumber;
        $this->shippingCost = $shippingCost;
        $this->status = $status;
        $this->estimatedDelivery = $estimatedDelivery;
    }

    // Getters and setters here
}
