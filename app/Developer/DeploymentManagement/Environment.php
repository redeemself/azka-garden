<?php
namespace App\Developer\DeploymentManagement;

class Environment
{
    public int $id;
    public string $name;
    public ?string $url;
    public ?string $type;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->url = $data['url'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
