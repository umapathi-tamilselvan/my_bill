<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 100.00,
            'stock' => 50,
            'unit' => 'pcs',
            'tax_rate' => 10,
            'is_active' => true,
        ];

        $response = $this->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 100.00,
        ]);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Original Name',
            'price' => 50.00,
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'price' => 75.00,
            'stock' => $product->stock,
            'unit' => $product->unit,
            'tax_rate' => $product->tax_rate,
            'is_active' => $product->is_active,
        ];

        $response = $this->put(route('products.update', $product->id), $updateData);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'price' => 75.00,
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }
}

