<?php
namespace App\Developer\DeveloperManagement;

class DeveloperPermission
{
    public int $id;
    public int $developerId;
    public string $module;
    public bool $canView;
    public bool $canCommit;
    public bool $canMerge;
    public bool $canDeploy;
    public int $interfaceId;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->developerId = $data['developer_id'] ?? 0;
        $this->module = $data['module'] ?? '';
        $this->canView = $data['can_view'] ?? false;
        $this->canCommit = $data['can_commit'] ?? false;
        $this->canMerge = $data['can_merge'] ?? false;
        $this->canDeploy = $data['can_deploy'] ?? false;
        $this->interfaceId = $data['interface_id'] ?? 11;
        $this->createdAt = isset($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime();
        $this->updatedAt = isset($data['updated_at']) ? new \DateTime($data['updated_at']) : new \DateTime();
    }
}
