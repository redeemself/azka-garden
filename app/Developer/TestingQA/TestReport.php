<?php
namespace App\Developer\TestingQA;

class TestReport
{
    public int $id;
    public int $testId;
    public ?string $actualResult;
    public ?string $status;
    public ?int $executedBy; // developer_id nullable
    public ?\DateTime $executedAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->testId = $data['test_id'] ?? 0;
        $this->actualResult = $data['actual_result'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->executedBy = $data['executed_by'] ?? null;
        $this->executedAt = isset($data['executed_at']) ? new \DateTime($data['executed_at']) : null;
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
