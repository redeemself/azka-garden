<?php
namespace App\User\ShippingManagement;

interface IShippingService
{
    public function createShipping(int $orderId, array $shippingDetails): bool;
    public function trackShipment(string $trackingNumber): array;
}
