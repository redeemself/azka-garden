<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\EnumRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pilih secara acak salah satu enum_role yang sudah ada.
        $enum = EnumRole::inRandomOrder()->first();

        return [
            'enum_role_id' => $enum?->id ?? EnumRole::factory(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}
