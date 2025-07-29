<?php
namespace App\Admin\OrderManagement;

class RefundManagement
{
    public int $id;
    public int $orderId;
    public ?float $amount;
    public ?string $reason;
    public ?string $status;
    public ?int $processedBy;
    public ?\DateTime $processedAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->orderId = $data['order_id'];
        $this->amount = isset($data['amount']) ? (float)$data['amount'] : null;
        $this->reason = $data['reason'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->processedBy = $data['processed_by'] ?? null;
        $this->processedAt = isset($data['processed_at']) ? new \DateTime($data['processed_at']) : null;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
