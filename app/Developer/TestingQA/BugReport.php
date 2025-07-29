<?php
namespace App\Developer\TestingQA;

class BugReport
{
    public int $id;
    public string $title;
    public ?string $description;
    public ?string $severity;
    public ?string $status;
    public ?int $assignedTo; // developer_id nullable
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->severity = $data['severity'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->assignedTo = $data['assigned_to'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
