<?php
namespace App\Admin\SecurityAudit;

class SecurityLog
{
    public int $id;
    public ?int $relatedTo;
    public ?string $event;
    public ?string $description;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->relatedTo = $data['related_to'] ?? null;
        $this->event = $data['event'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
