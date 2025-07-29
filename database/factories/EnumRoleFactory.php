<?php

namespace Database\Factories;

use App\Models\EnumRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnumRoleFactory extends Factory
{
    protected $model = EnumRole::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->randomElement(['CUSTOMER', 'GUEST']),
        ];
    }
}
