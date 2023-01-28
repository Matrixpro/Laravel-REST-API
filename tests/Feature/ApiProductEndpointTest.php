<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

// php artisan test Tests\Feature\ApiProductEndpointTest.php

class ApiProductEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a user and seed some products for the tests
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Product::factory(100)->for($this->user)->create();
    }

    /**
     * Tests that a token can be created with permissions
     * @return void
     */
    public function test_token_product_permissions()
    {
        $this->actingAs($this->user);

        $perms = [
            'product:create',
            'product:read',
            'product:update',
            'product:delete',
        ];

        /*
         * Confirm that test perms are same as actual perms
         */

        $this->assertSame($perms, Product::$permissions);

        /*
         * Create the token and check permissions
         */

        $token = $this->user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => $perms,
        ]);

        foreach (Product::$permissions as $permission)
            $this->assertTrue($token->can($permission));
    }

    /**
     * Tests that a user can get products via product api endpoint
     * @return void
     */
    public function test_user_can_get_products()
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/product');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'path',
                    'per_page',
                    'next_cursor',
                    'prev_cursor',
                ],
            ]);
    }

    /**
     * Tests that a user can get a product via product api endpoint
     * @return void
     */
    public function test_user_can_get_a_product()
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/product/1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data',
            ]);
    }

    /**
     * Tests that a user can get products via product api endpoint
     * @return void
     */
    public function test_user_can_filter_products()
    {
        $this->actingAs($this->user);

        Product::factory(1)->for($this->user)->create(['name' => 'Widget']);

        $response = $this->getJson('/api/product?filter[name]=Widget');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'path',
                    'per_page',
                    'next_cursor',
                    'prev_cursor',
                ],
            ]);
    }

    /**
     * Tests that a user can get products via product api endpoint
     * @return void
     */
    public function test_user_can_not_use_bad_filter()
    {
        $this->actingAs($this->user);

        Product::factory(1)->for($this->user)->create(['name' => 'Widget']);

        $response = $this->getJson('/api/product?filter[bad]=Widget');

        $response->assertStatus(500);
    }

    /**
     * Tests that a user can create and use tokens
     * @return void
     */
    public function test_user_can_create_and_use_token()
    {
        /*
         * Create the API token
         */

        $payload = ['email' => $this->user->email, 'password' => 'password', 'device_name' => 'iphone'];

        $response = $this->postJson('/api/token', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ],
                'success',
                'message',
            ]);

        $content = $response->decodeResponseJson();

        $token = $content['data']['token'];

        $response_b = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/product');

        /*
         * Use token to get products, check response code, result count, and JSON structure
         */

        $response_b->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data',
                'links' => [
                    'next',
                ],
                'meta' => [
                    'path',
                    'per_page',
                    'next_cursor',
                    'prev_cursor',
                ],
            ]);

        /*
         * Run same test again but use cursor
         */

        $content = $response_b->decodeResponseJson();
        $first_row = $content['data'][0];
        $cursor = $content['meta']['next_cursor'];

        $response_c = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/product?cursor='.$cursor);

        $content = $response_c->decodeResponseJson();
        $second_row = $content['data'][0];

        /*
         * Test cursor pagination by checking that results are different
         */

        $this->assertNotSame($first_row, $second_row);

        /*
         * Check response code, result count, and JSON structure
         */

        $response_c->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data',
                'links' => [
                    'next',
                ],
                'meta' => [
                    'path',
                    'per_page',
                    'next_cursor',
                    'prev_cursor',
                ],
            ]);
    }


}
