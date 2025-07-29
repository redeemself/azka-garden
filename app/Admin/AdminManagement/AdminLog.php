<?php
namespace App\Admin\AdminManagement;

class AdminLog
{
    public int $id;
    public int $adminId;
    public string $action;
    public ?string $description;
    public ?string $ipAddress;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->adminId = $data['admin_id'];
        $this->action = $data['action'];
        $this->description = $data['description'] ?? null;
        $this->ipAddress = $data['ip_address'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
