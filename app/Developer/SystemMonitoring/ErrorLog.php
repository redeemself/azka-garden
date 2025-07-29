<?php
namespace App\Developer\SystemMonitoring;

class ErrorLog
{
    public int $id;
    public string $level;
    public string $message;
    public ?string $stackTrace;
    public ?string $source;
    public \DateTime $timestamp;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->level = $data['level'] ?? 'ERROR';
        $this->message = $data['message'] ?? '';
        $this->stackTrace = $data['stack_trace'] ?? null;
        $this->source = $data['source'] ?? null;
        $this->timestamp = new \DateTime($data['timestamp'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
