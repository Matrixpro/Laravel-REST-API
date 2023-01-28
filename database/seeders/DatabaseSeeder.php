<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
        ]);
        //
        // Product::factory(100)->for($user)->create();
        // token: KeegPHTY0zZWaeso99GId02n9bDBKS9EAgjiDaAS
        $user = User::first();
        $products = Product::factory(2)->for($user)->create();
        $categories = Category::factory(2)->for($user)->create();

        // Sync products to categories
        $product_ids = $products->pluck('id')->all();

        foreach ($categories as $category)
            $category->products()->sync($product_ids);
    }
}
