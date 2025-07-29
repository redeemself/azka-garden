<?php
namespace App\User\UserManagement;

use App\User\Core\IRepository;
use App\User\Core\IEntity;

class UserRepository implements IRepository
{
    public function find(int $id): ?IEntity
    {
        // Query DB and return User entity or null
        return null;
    }

    public function save(IEntity $entity): bool
    {
        // Save or update User entity to DB
        return true;
    }

    public function delete(int $id): bool
    {
        // Delete User by id from DB
        return true;
    }
}
