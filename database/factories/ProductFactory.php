<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // Buat category baru via factory atau pilih id yang sudah ada
            'category_id'  => Category::factory(),
            'name'         => $this->faker->words(3, true),
            'description'  => $this->faker->paragraph(),
            'stock'        => $this->faker->numberBetween(0, 100),
            'price'        => $this->faker->randomFloat(2, 1, 1000),
            'weight'       => $this->faker->randomFloat(2, 0.1, 10),
            'image_url'    => $this->faker->imageUrl(640, 480, 'nature', true),
            'status'       => $this->faker->boolean(80),
            'interface_id' => 1, // IEntity
        ];
    }
}
