<?php
namespace App\Developer\DeploymentManagement;

class ReleaseNote
{
    public int $id;
    public int $deploymentId;
    public ?string $content;
    public ?int $createdBy; // developer_id nullable
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->deploymentId = $data['deployment_id'] ?? 0;
        $this->content = $data['content'] ?? null;
        $this->createdBy = $data['created_by'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
