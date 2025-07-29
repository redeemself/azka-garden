<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isDeveloper();
    }

    public function view(User $user, Product $product)
    {
        return $user->isAdmin() || $user->isDeveloper();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Product $product)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Product $product)
    {
        return $user->isAdmin();
    }
}
