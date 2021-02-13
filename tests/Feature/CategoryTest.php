<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryTest extends TestCase
{

    public function testApi()
    {
        $response = $this->getJson('/api/categories/search');
        $response->assertStatus(200)->assertJson([
            'data' => true
        ]);
    }
}
