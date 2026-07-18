<?php

namespace Tests\Feature;

use App\Mail\NewContactMessage;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_contact_form()
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name' => 'Anon',
            'email' => 'anon@example.com',
            'title' => 'Hello',
            'message' => 'Test message',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('contacts', [
            'email' => 'anon@example.com',
            'title' => 'Hello',
        ]);
    }

    public function test_contact_form_requires_all_fields()
    {
        Mail::fake();

        $response = $this->post('/contact', ['name' => 'Anon']);

        $response->assertSessionHasErrors(['email', 'title', 'message']);
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_contact_submission_emails_the_admin()
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Anon',
            'email' => 'anon@example.com',
            'title' => 'Hello',
            'message' => 'Test message',
        ]);

        Mail::assertSent(NewContactMessage::class, function ($mail) {
            return $mail->contact->email === 'anon@example.com'
                && $mail->hasTo(config('mail.admin_address'));
        });
    }

    public function test_guest_cannot_view_contact_index()
    {
        $this->get('/contact')->assertRedirect('/login');
    }

    public function test_guest_cannot_view_a_single_contact()
    {
        $contact = Contact::create([
            'name' => 'Anon', 'email' => 'anon@example.com',
            'title' => 'Hello', 'message' => 'Test',
        ]);

        $this->get("/contact/{$contact->id}")->assertRedirect('/login');
    }
}
