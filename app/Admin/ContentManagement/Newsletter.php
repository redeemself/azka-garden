<?php
namespace App\Admin\ContentManagement;

class Newsletter
{
    public int $id;
    public ?string $subject;
    public ?string $content;
    public ?string $recipientType;
    public ?string $status;
    public ?\DateTime $scheduledAt;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->subject = $data['subject'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->recipientType = $data['recipient_type'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->scheduledAt = isset($data['scheduled_at']) ? new \DateTime($data['scheduled_at']) : null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
