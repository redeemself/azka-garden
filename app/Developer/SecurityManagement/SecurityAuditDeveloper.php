<?php
namespace App\Developer\SecurityManagement;

class SecurityAuditDeveloper
{
    public int $id;
    public string $type;
    public ?string $description;
    public ?string $severity;
    public ?string $status;
    public ?string $findings;
    public ?int $developerId;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['type'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->severity = $data['severity'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->findings = $data['findings'] ?? null;
        $this->developerId = $data['developer_id'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
