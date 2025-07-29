<?php
namespace App\Developer\APIManagement;

class APIEndpoint
{
    public int $id;
    public string $path;
    public string $method;
    public ?string $version;
    public ?string $description;
    public bool $authRequired;
    public ?int $rateLimit;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->path = $data['path'] ?? '';
        $this->method = $data['method'] ?? 'GET';
        $this->version = $data['version'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->authRequired = $data['auth_required'] ?? false;
        $this->rateLimit = $data['rate_limit'] ?? null;
        $this->createdAt = isset($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime();
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
