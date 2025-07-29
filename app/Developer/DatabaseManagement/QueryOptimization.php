<?php
namespace App\Developer\DatabaseManagement;

class QueryOptimization
{
    public int $id;
    public string $queryText;
    public int $executionTime;
    public ?string $suggestedOptimization;
    public ?string $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->queryText = $data['query_text'] ?? '';
        $this->executionTime = $data['execution_time'] ?? 0;
        $this->suggestedOptimization = $data['suggested_optimization'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
