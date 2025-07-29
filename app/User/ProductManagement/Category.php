<?php
namespace App\User\ProductManagement;

class Category
{
    private int $id;
    private string $name;
    private ?string $description;
    private ?string $icon;
    private bool $status;

    public function __construct(
        int $id,
        string $name,
        ?string $description = null,
        ?string $icon = null,
        bool $status = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;
        $this->status = $status;
    }

    // Getters and setters here
}
