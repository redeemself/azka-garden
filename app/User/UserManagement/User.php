<?php
namespace App\User\UserManagement;

use App\User\Core\IEntity;

class User implements IEntity
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private ?string $phone;

    public function __construct(int $id, string $name, string $email, string $password, ?string $phone = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
    }

    public function getId(): int
    {
        return $this->id;
    }

    // Add getters and setters as needed
}
