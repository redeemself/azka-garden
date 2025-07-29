<?php
namespace App\Developer\DatabaseManagement;

class DatabaseConfig
{
    public int $id;
    public string $dbName;
    public string $host;
    public int $port;
    public string $username;
    public ?string $password;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->dbName = $data['db_name'] ?? '';
        $this->host = $data['host'] ?? 'localhost';
        $this->port = $data['port'] ?? 3306;
        $this->username = $data['username'] ?? '';
        $this->password = $data['password'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
