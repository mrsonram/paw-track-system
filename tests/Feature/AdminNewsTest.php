<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_news_list()
    {
        $admin = User::factory()->create();
        News::create(['title' => 'ListedNews', 'subtitle' => 'sub', 'detail' => 'detail']);

        $response = $this->actingAs($admin)->get('/message');

        $response->assertStatus(200)->assertSee('ListedNews');
    }

    public function test_admin_can_create_news()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/message', [
            'title' => 'New Announcement',
            'subtitle' => 'Sub',
            'detail' => 'Details here',
        ]);

        $response->assertRedirect('/message');
        $this->assertDatabaseHas('news', ['title' => 'New Announcement']);
    }

    public function test_news_creation_requires_fields()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/message', ['title' => 'Only Title']);

        $response->assertSessionHasErrors(['subtitle', 'detail']);
        $this->assertDatabaseCount('news', 0);
    }

    public function test_admin_can_update_news()
    {
        $admin = User::factory()->create();
        $news = News::create(['title' => 'Old', 'subtitle' => 'sub', 'detail' => 'detail']);

        $response = $this->actingAs($admin)->put("/message/{$news->id}", [
            'title' => 'Updated',
            'subtitle' => 'sub',
            'detail' => 'detail',
        ]);

        $response->assertRedirect('/message');
        $this->assertDatabaseHas('news', ['id' => $news->id, 'title' => 'Updated']);
    }

    public function test_admin_can_delete_news()
    {
        $admin = User::factory()->create();
        $news = News::create(['title' => 'ToDelete', 'subtitle' => 'sub', 'detail' => 'detail']);

        $response = $this->actingAs($admin)->delete("/message/{$news->id}");

        $response->assertRedirect('message');
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }
}
