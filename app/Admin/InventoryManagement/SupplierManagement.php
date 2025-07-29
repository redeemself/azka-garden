<?php
namespace App\Admin\InventoryManagement;

class SupplierManagement
{
    public int $id;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $address;
    public bool $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->status = isset($data['status']) ? (bool)$data['status'] : true;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
