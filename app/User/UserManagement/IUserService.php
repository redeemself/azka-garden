<?php
namespace App\User\UserManagement;

use App\User\Core\IEntity;

interface IUserService
{
    public function register(array $data): IEntity;
    public function login(string $email, string $password): ?IEntity;
    public function logout(int $userId): bool;
    public function getUserById(int $id): ?IEntity;
}
