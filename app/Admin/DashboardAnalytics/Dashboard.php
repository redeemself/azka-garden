<?php
namespace App\Admin\DashboardAnalytics;

class Dashboard
{
    public int $id;
    public ?string $title;
    public array $layout;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? null;
        $this->layout = json_decode($data['layout'] ?? '[]', true);
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
