<?php
namespace App\Developer\DeploymentManagement;

class Deployment
{
    public int $id;
    public string $version;
    public \DateTime $date;
    public ?string $notes;
    public ?string $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->version = $data['version'] ?? '';
        $this->date = new \DateTime($data['date'] ?? 'now');
        $this->notes = $data['notes'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
