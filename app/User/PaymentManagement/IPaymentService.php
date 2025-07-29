<?php
namespace App\User\PaymentManagement;

interface IPaymentService
{
    public function processPayment(int $orderId, array $paymentDetails): bool;
    public function getPaymentStatus(int $paymentId): string;
}
