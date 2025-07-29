<?php
namespace App\Developer\Core;

interface IDevRepository
{
    public function find(int $id);
    public function save($entity): bool;
    public function delete(int $id): bool;
}
