<?php
namespace App\Developer\APIManagement;

class APIMetrics
{
    public int $id;
    public int $endpointId;
    public \DateTime $timestamp;
    public int $responseTime;
    public int $statusCode;
    public ?float $errorRate;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->endpointId = $data['endpoint_id'] ?? 0;
        $this->timestamp = new \DateTime($data['timestamp'] ?? 'now');
        $this->responseTime = $data['response_time'] ?? 0;
        $this->statusCode = $data['status_code'] ?? 200;
        $this->errorRate = $data['error_rate'] ?? null;
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
