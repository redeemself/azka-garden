<?php
namespace App\Admin\SecurityAudit;

class AdminSession
{
    public int $id;
    public ?int $adminId;
    public ?\DateTime $loginTime;
    public ?\DateTime $logoutTime;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->adminId = $data['admin_id'] ?? null;
        $this->loginTime = isset($data['login_time']) ? new \DateTime($data['login_time']) : null;
        $this->logoutTime = isset($data['logout_time']) ? new \DateTime($data['logout_time']) : null;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
