<?php
namespace App\Admin\DashboardAnalytics;

use App\Admin\Core\StatsType;

class Statistics
{
    public int $id;
    public StatsType $type;
    public string $period;
    public array $data;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->type = $data['enum_stats_type_id']; // Ideally map to StatsType enum
        $this->period = $data['period'];
        $this->data = json_decode($data['data'] ?? '[]', true);
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
