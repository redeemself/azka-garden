<?php
namespace App\User\Core;

interface IRepository
{
    public function find(int $id): ?IEntity;
    public function save(IEntity $entity): bool;
    public function delete(int $id): bool;
}
