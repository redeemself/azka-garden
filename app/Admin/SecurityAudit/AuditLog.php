<?php
namespace App\Admin\SecurityAudit;

class AuditLog
{
    public int $id;
    public ?int $recordedBy;
    public ?string $action;
    public ?string $details;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->recordedBy = $data['recorded_by'] ?? null;
        $this->action = $data['action'] ?? null;
        $this->details = $data['details'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
