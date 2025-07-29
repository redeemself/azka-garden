<?php
namespace App\Admin\Core;

interface IAdminService
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getById(int $id);
}
