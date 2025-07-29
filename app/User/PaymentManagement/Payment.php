<?php
namespace App\User\PaymentManagement;

class Payment
{
    private int $id;
    private int $orderId;
    private int $methodId;
    private string $transactionCode;
    private ?string $bankAccount;
    private float $total;
    private int $paymentStatusId;
    private ?string $proofOfPayment;
    private ?\DateTime $expiredAt;

    public function __construct(
        int $id,
        int $orderId,
        int $methodId,
        string $transactionCode,
        ?string $bankAccount,
        float $total,
        int $paymentStatusId,
        ?string $proofOfPayment = null,
        ?\DateTime $expiredAt = null
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->methodId = $methodId;
        $this->transactionCode = $transactionCode;
        $this->bankAccount = $bankAccount;
        $this->total = $total;
        $this->paymentStatusId = $paymentStatusId;
        $this->proofOfPayment = $proofOfPayment;
        $this->expiredAt = $expiredAt;
    }

    // Getters and setters here
}
