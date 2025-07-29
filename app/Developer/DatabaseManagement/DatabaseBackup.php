<?php
namespace App\Developer\DatabaseManagement;

class DatabaseBackup
{
    public int $id;
    public string $dbName;
    public ?string $backupType;
    public ?string $filePath;
    public ?int $size;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->dbName = $data['db_name'] ?? '';
        $this->backupType = $data['backup_type'] ?? null;
        $this->filePath = $data['file_path'] ?? null;
        $this->size = isset($data['size']) ? intval($data['size']) : null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 11;
    }
}
