<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\MapLocation;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function makeDog(array $overrides = []): Animal
    {
        return Animal::create(array_merge([
            'name' => 'Rex',
            'species' => 'พันธุ์ไทย',
            'marking' => 'n/a',
            'gender' => 'M',
            'collar' => 'n/a',
            'age' => '2',
            'status' => 'มีชีวิตอยู่',
            'vet' => 'n/a',
            'owner' => 'n/a',
            'image' => 'n/a',
            'location' => 'n/a',
            'lat' => '14.1',
            'lng' => '100.6',
        ], $overrides));
    }

    public function test_homepage_loads()
    {
        $this->makeDog();

        $this->get('/')->assertStatus(200)->assertSee('Rex');
    }

    public function test_info_page_lists_dogs()
    {
        $this->makeDog(['name' => 'Rex']);
        $this->makeDog(['name' => 'Fido']);

        $response = $this->get('/info');

        $response->assertStatus(200)->assertSee('Rex')->assertSee('Fido');
    }

    public function test_info_page_filters_by_species()
    {
        $this->makeDog(['name' => 'ThaiDog', 'species' => 'พันธุ์ไทย']);
        $this->makeDog(['name' => 'PoodleDog', 'species' => 'พุดเดิ้ล']);

        $response = $this->get('/info?species=พุดเดิ้ล');

        $response->assertStatus(200)->assertSee('PoodleDog')->assertDontSee('ThaiDog');
    }

    public function test_info_page_filters_by_status()
    {
        $this->makeDog(['name' => 'AliveDog', 'status' => 'มีชีวิตอยู่']);
        $this->makeDog(['name' => 'DeceasedDog', 'status' => 'เสียชีวิตแล้ว']);

        $response = $this->get('/info?status=' . urlencode('เสียชีวิตแล้ว'));

        $response->assertStatus(200)->assertSee('DeceasedDog')->assertDontSee('AliveDog');
    }

    public function test_info_page_paginates()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->makeDog(['name' => "Dog{$i}"]);
        }

        $page1 = $this->get('/info');
        $page2 = $this->get('/info?page=2');

        $page1->assertStatus(200)->assertSee('Dog1');
        $page2->assertStatus(200);
    }

    public function test_news_page_loads()
    {
        News::create(['title' => 'ข่าวทดสอบ', 'subtitle' => 'sub', 'detail' => 'detail']);

        $this->get('/news')->assertStatus(200)->assertSee('ข่าวทดสอบ');
    }

    public function test_map_page_loads_and_shows_dogs_and_pins()
    {
        $this->makeDog(['name' => 'MapDog']);
        MapLocation::create(['name' => 'MapPin', 'lat' => '14.2', 'lng' => '100.7']);

        $response = $this->get('/map');

        $response->assertStatus(200)->assertSee('MapDog')->assertSee('MapPin');
    }

    public function test_about_page_loads()
    {
        $this->get('/about')->assertStatus(200);
    }

    public function test_dog_show_page_loads_for_existing_dog()
    {
        $dog = $this->makeDog(['name' => 'ShowDog']);

        $this->get("/pet/show/{$dog->id}")->assertStatus(200)->assertSee('ShowDog');
    }

    public function test_dog_show_page_404s_for_missing_dog()
    {
        $this->get('/pet/show/99999')->assertStatus(404);
    }

    public function test_news_show_page_loads()
    {
        $news = News::create(['title' => 'ShowNews', 'subtitle' => 'sub', 'detail' => 'detail']);

        $this->get("/news/show/{$news->id}")->assertStatus(200)->assertSee('ShowNews');
    }
}
