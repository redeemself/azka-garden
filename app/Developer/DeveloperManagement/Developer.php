<?php
namespace App\Developer\DeveloperManagement;

use App\Developer\Core\DevRole;
use App\Developer\Core\DevStatus;

class Developer
{
    public int $id;
    public string $name;
    public string $username;
    public string $password;
    public string $email;
    public DevRole $role;
    public DevStatus $status;
    public ?string $specialization;
    public ?string $githubProfile;
    public ?\DateTime $lastLogin;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->username = $data['username'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->role = DevRole::from($data['role'] ?? DevRole::BACKEND_DEVELOPER->value);
        $this->status = DevStatus::from($data['status'] ?? DevStatus::ACTIVE->value);
        $this->specialization = $data['specialization'] ?? null;
        $this->githubProfile = $data['github_profile'] ?? null;
        $this->lastLogin = isset($data['last_login']) ? new \DateTime($data['last_login']) : null;
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
