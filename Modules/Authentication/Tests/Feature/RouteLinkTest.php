<?php

namespace Modules\Authentication\Tests\Feature;

use Tests\TestCase;

class RouteLinkTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_page_found()
    {
        $response = $this->getJson('api/test');
        $response->assertOk();
        $response->assertStatus(200);
    }

    public function test_page_not_found()
    {
        $response = $this->getJson('api/fdsf');
        $response->assertNotFound();
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'data' => [],
                'errors' => 'Page not found',
                'message' => '',
                'status' => 404
            ]);
    }
}
