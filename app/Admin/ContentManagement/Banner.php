<?php
namespace App\Admin\ContentManagement;

class Banner
{
    public int $id;
    public ?string $title;
    public ?string $image;
    public ?string $link;
    public ?string $position;
    public ?\DateTime $startDate;
    public ?\DateTime $endDate;
    public bool $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? null;
        $this->image = $data['image'] ?? null;
        $this->link = $data['link'] ?? null;
        $this->position = $data['position'] ?? null;
        $this->startDate = isset($data['start_date']) ? new \DateTime($data['start_date']) : null;
        $this->endDate = isset($data['end_date']) ? new \DateTime($data['end_date']) : null;
        $this->status = $data['status'] ?? true;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
