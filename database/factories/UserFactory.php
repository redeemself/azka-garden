<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => Str::random(10),
            'phone'             => $this->faker->phoneNumber(),
            'last_login'        => $this->faker->dateTime(),
            'role_id'           => 1, // Pastikan role dengan id=1 sudah ada via seeder
            'interface_id'      => 1, // Pastikan interface id=1 (IEntity) sudah ada
        ];
    }
}
