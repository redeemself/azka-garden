<?php
namespace App\Admin\InventoryManagement;

class PurchaseOrder
{
    public int $id;
    public int $supplierId;
    public ?string $status;
    public ?float $totalAmount;
    public ?string $paymentStatus;
    public ?\DateTime $deliveryDate;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->supplierId = $data['supplier_id'];
        $this->status = $data['status'] ?? null;
        $this->totalAmount = isset($data['total_amount']) ? (float)$data['total_amount'] : null;
        $this->paymentStatus = $data['payment_status'] ?? null;
        $this->deliveryDate = isset($data['delivery_date']) ? new \DateTime($data['delivery_date']) : null;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
