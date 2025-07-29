<?php
namespace App\Admin\OrderManagement;

class OrderManagement
{
    public int $id;
    public int $orderId;
    public int $adminId;
    public string $action;
    public ?string $notes;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->orderId = $data['order_id'];
        $this->adminId = $data['admin_id'];
        $this->action = $data['action'];
        $this->notes = $data['notes'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
