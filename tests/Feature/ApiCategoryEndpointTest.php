<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCategoryEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // The acting user
        $this->user = User::factory()->create();

        // Test products and categories
        $products = Product::factory(2)->for($this->user)->create();
        $categories = Category::factory(2)->for($this->user)->create();

        // Sync products to categories
        $product_ids = $products->pluck('id')->all();

        foreach ($categories as $category)
            $category->products()->sync($product_ids);
    }

    /**
     * Tests that a user can get categories via product api endpoint
     * @return void
     */
    public function test_user_can_get_categories()
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/category');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta',
            ]);
    }

    /**
     * Tests that a user can get a category using filter[id] via product api endpoint
     * @return void
     */
    public function test_user_can_get_a_category()
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/category?filter[id]=1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data'=> [['id', 'name']],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta',
            ]);
    }

    /**
     * Tests that a user can get categories with products via category api endpoint
     * @return void
     */
    public function test_user_can_get_a_category_with_products()
    {
        $payload = ['include' => 'products'];

        $this->actingAs($this->user);

        $response = $this->getJson('/api/category?include=products', $payload);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [['id', 'name', 'products']],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta',
            ]);
    }
}
