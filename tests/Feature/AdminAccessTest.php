<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider protectedRoutesProvider
     */
    public function test_guest_is_redirected_to_login($method, $uri)
    {
        $response = $this->call($method, $uri);

        $response->assertRedirect('/login');
    }

    public function protectedRoutesProvider()
    {
        return [
            'admin dashboard' => ['GET', '/home'],
            'dog index' => ['GET', '/dog'],
            'dog create form' => ['GET', '/dog/create'],
            'news index' => ['GET', '/message'],
            'news create form' => ['GET', '/message/create'],
            'contact index' => ['GET', '/contact'],
            'map add form' => ['GET', '/google/add'],
        ];
    }

    public function test_guest_cannot_post_to_map_add()
    {
        $response = $this->post('/google/add', ['name' => 'x', 'lat' => '1', 'lng' => '1']);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('map_locations', 0);
    }
}
