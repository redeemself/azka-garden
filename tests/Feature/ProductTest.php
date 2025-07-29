<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testProductListPage()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function testCreateProduct()
    {
        $product = Product::factory()->make();

        $response = $this->post('/admin/products', $product->toArray());
        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }
}
