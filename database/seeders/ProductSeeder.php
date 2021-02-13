<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory()->count(12)->create();
        foreach ($products as $product) {
            $categories = Category::orderByRaw("RAND()")->limit(rand(1, 3))->pluck('id')->toArray();
            $product->categories()->sync($categories);
        }
    }
}
