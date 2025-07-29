<?php
namespace App\Admin\Core;

interface IAdminRepository
{
    public function findById(int $id);
    public function findAll();
    public function save($entity);
    public function delete(int $id);
}
