<?php
namespace App\Developer\SystemMonitoring;

class Performance
{
    public int $id;
    public string $metricName;
    public float $value;
    public ?string $unit;
    public \DateTime $timestamp;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->metricName = $data['metric_name'] ?? '';
        $this->value = floatval($data['value'] ?? 0);
        $this->unit = $data['unit'] ?? null;
        $this->timestamp = new \DateTime($data['timestamp'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
