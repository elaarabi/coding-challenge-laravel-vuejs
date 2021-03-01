<?php

namespace Tests\Feature;

use App\Models\Product\Category;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Test Product creation
     */
    public function testApi()
    {
        $response = $this->postJson('/api/product', [
            'name' => 'Test API',
            'description' => 'Test Description',
            'price' => 10,
            'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'categories' => Category::orderByRaw("RAND()")->limit(rand(1, 3))->pluck('id')->toArray()
        ]);
        $response->assertStatus(200)->assertJson([
            'data' => true
        ]);
    }
}
