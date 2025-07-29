<?php
namespace App\User\OrderManagement;

interface IOrderService
{
    public function addToCart(int $userId, int $productId, int $quantity, ?string $note = null): bool;
    public function placeOrder(int $userId, array $orderDetails): bool;
    public function getOrder(int $orderId);
    public function cancelOrder(int $orderId): bool;
}
