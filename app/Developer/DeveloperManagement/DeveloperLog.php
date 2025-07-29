<?php
namespace App\Developer\DeveloperManagement;

class DeveloperLog
{
    public int $id;
    public int $developerId;
    public string $action;
    public ?string $description;
    public ?string $ipAddress;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->developerId = $data['developer_id'] ?? 0;
        $this->action = $data['action'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->ipAddress = $data['ip_address'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
