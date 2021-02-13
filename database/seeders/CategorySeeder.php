<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::factory()->count(5)->create();
        foreach ($categories as $category) {
            $category->children()->saveMany(Category::factory()
                ->count(rand(1,3))
                ->create());
        }
    }
}
