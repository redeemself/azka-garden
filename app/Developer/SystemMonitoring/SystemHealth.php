<?php
namespace App\Developer\SystemMonitoring;

class SystemHealth
{
    public int $id;
    public string $component;
    public string $status;
    public ?float $cpuUsage;
    public ?float $memoryUsage;
    public ?float $diskUsage;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->component = $data['component'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->cpuUsage = isset($data['cpu_usage']) ? floatval($data['cpu_usage']) : null;
        $this->memoryUsage = isset($data['memory_usage']) ? floatval($data['memory_usage']) : null;
        $this->diskUsage = isset($data['disk_usage']) ? floatval($data['disk_usage']) : null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
