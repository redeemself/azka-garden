<?php
namespace App\Developer\TestingQA;

class TestCase
{
    public int $id;
    public string $title;
    public ?string $description;
    public ?string $testType;
    public ?string $expectedResult;
    public ?string $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->testType = $data['test_type'] ?? null;
        $this->expectedResult = $data['expected_result'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
