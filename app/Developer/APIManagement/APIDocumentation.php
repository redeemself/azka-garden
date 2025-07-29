<?php
namespace App\Developer\APIManagement;

class APIDocumentation
{
    public int $id;
    public int $endpointId;
    public ?string $version;
    public ?string $content;
    public ?array $examples;
    public ?int $updatedBy;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->endpointId = $data['endpoint_id'] ?? 0;
        $this->version = $data['version'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->examples = isset($data['examples']) ? json_decode($data['examples'], true) : null;
        $this->updatedBy = $data['updated_by'] ?? null;
        $this->createdAt = isset($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime();
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
