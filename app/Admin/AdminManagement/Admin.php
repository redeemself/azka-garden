<?php
namespace App\Admin\AdminManagement;

use App\Admin\Core\AdminRole;
use App\Admin\Core\AdminStatus;

class Admin
{
    public int $id;
    public string $username;
    public string $password; // hashed
    public string $fullName;
    public string $email;
    public AdminRole $role;
    public AdminStatus $status;
    public ?\DateTime $lastLogin;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->username = $data['username'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->fullName = $data['full_name'] ?? '';
        $this->email = $data['email'] ?? '';

        // Menggunakan tryFrom agar tidak error jika nilai string tidak valid
        $this->role = isset($data['role']) ? AdminRole::tryFrom($data['role']) ?? AdminRole::SUPER_ADMIN : AdminRole::SUPER_ADMIN;
        $this->status = isset($data['status']) ? AdminStatus::tryFrom($data['status']) ?? AdminStatus::ACTIVE : AdminStatus::ACTIVE;

        $this->lastLogin = !empty($data['last_login']) ? new \DateTime($data['last_login']) : null;

        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
