<?php
namespace App\Developer\SecurityManagement;

class SecurityConfig
{
    public int $id;
    public string $component;
    public string $configKey;
    public ?string $configValue;
    public bool $isEncrypted;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->component = $data['component'] ?? '';
        $this->configKey = $data['config_key'] ?? '';
        $this->configValue = $data['config_value'] ?? null;
        $this->isEncrypted = $data['is_encrypted'] ?? false;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
