<?php

namespace Tests\Feature;

use App\Models\MapLocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_add_a_map_pin_with_only_name_lat_lng()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/google/add', [
            'name' => 'PinTest',
            'lat' => '14.15',
            'lng' => '100.62',
        ]);

        $response->assertRedirect('/google/add');
        $this->assertDatabaseHas('map_locations', ['name' => 'PinTest']);
    }

    public function test_map_pin_requires_name_lat_lng()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/google/add', ['name' => 'PinTest']);

        $response->assertSessionHasErrors(['lat', 'lng']);
        $this->assertDatabaseCount('map_locations', 0);
    }

    public function test_map_pin_show_page_loads()
    {
        $pin = MapLocation::create(['name' => 'ShowPin', 'lat' => '14.1', 'lng' => '100.6']);

        $this->get("/google/{$pin->id}")->assertStatus(200)->assertSee('ShowPin');
    }
}
