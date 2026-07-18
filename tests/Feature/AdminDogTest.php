<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDogTest extends TestCase
{
    use RefreshDatabase;

    private function validDogPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Rex',
            'species' => 'พันธุ์ไทย',
            'marking' => 'n/a',
            'gender' => 'M',
            'collar' => 'n/a',
            'age' => '2',
            'status' => 'มีชีวิตอยู่',
            'vet' => 'n/a',
            'owner' => 'n/a',
            'location' => 'สวนสาธารณะ',
            'lat' => '14.1',
            'lng' => '100.6',
            'image' => UploadedFile::fake()->image('dog.jpg'),
        ], $overrides);
    }

    public function test_admin_can_view_dog_list()
    {
        $admin = User::factory()->create();
        Animal::create(array_merge($this->validDogPayload(['name' => 'ListedDog']), ['image' => 'n/a']));

        $response = $this->actingAs($admin)->get('/dog');

        $response->assertStatus(200)->assertSee('ListedDog');
    }

    public function test_admin_can_create_a_dog()
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/dog', $this->validDogPayload());

        $response->assertRedirect('/dog');
        $this->assertDatabaseHas('animals', ['name' => 'Rex', 'location' => 'สวนสาธารณะ']);
    }

    public function test_dog_creation_requires_an_image()
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $payload = $this->validDogPayload();
        unset($payload['image']);

        $response = $this->actingAs($admin)->post('/dog', $payload);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('animals', 0);
    }

    public function test_dog_creation_requires_required_fields()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/dog', ['name' => 'Rex']);

        $response->assertSessionHasErrors(['species', 'gender', 'status', 'location', 'image']);
    }

    public function test_admin_can_update_a_dog_without_reuploading_image()
    {
        $admin = User::factory()->create();
        $dog = Animal::create(array_merge($this->validDogPayload(['name' => 'Old']), ['image' => 'dogs/old.jpg']));

        $payload = $this->validDogPayload(['name' => 'Updated']);
        unset($payload['image']);

        $response = $this->actingAs($admin)->put("/dog/{$dog->id}", $payload);

        $response->assertRedirect('/dog');
        $this->assertDatabaseHas('animals', ['id' => $dog->id, 'name' => 'Updated', 'image' => 'dogs/old.jpg']);
    }

    public function test_admin_can_delete_a_dog()
    {
        $admin = User::factory()->create();
        $dog = Animal::create(array_merge($this->validDogPayload(['name' => 'ToDelete']), ['image' => 'n/a']));

        $response = $this->actingAs($admin)->delete("/dog/{$dog->id}");

        $response->assertRedirect('/dog');
        $this->assertDatabaseMissing('animals', ['id' => $dog->id]);
    }

    public function test_dog_list_search_filters_by_name()
    {
        $admin = User::factory()->create();
        Animal::create(array_merge($this->validDogPayload(['name' => 'FindMe']), ['image' => 'n/a']));
        Animal::create(array_merge($this->validDogPayload(['name' => 'Other']), ['image' => 'n/a']));

        $response = $this->actingAs($admin)->get('/dog?q=FindMe');

        $response->assertStatus(200)->assertSee('FindMe')->assertDontSee('Other');
    }
}
