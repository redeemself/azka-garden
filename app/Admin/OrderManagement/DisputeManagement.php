<?php
namespace App\Admin\OrderManagement;

class DisputeManagement
{
    public int $id;
    public int $orderId;
    public int $customerId;
    public string $type;
    public ?string $description;
    public ?string $status;
    public ?string $resolution;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->orderId = $data['order_id'];
        $this->customerId = $data['customer_id'];
        $this->type = $data['type'];
        $this->description = $data['description'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->resolution = $data['resolution'] ?? null;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
