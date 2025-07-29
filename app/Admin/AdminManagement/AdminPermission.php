<?php
namespace App\Admin\AdminManagement;

class AdminPermission
{
    public int $id;
    public int $adminId;
    public string $module;
    public bool $canView;
    public bool $canCreate;
    public bool $canEdit;
    public bool $canDelete;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->adminId = $data['admin_id'];
        $this->module = $data['module'];
        $this->canView = $data['can_view'] ?? false;
        $this->canCreate = $data['can_create'] ?? false;
        $this->canEdit = $data['can_edit'] ?? false;
        $this->canDelete = $data['can_delete'] ?? false;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
