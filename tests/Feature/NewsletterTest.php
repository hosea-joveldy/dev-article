<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_newsletter_with_valid_email(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
        ]);

        $response->assertSessionHas('newsletter_success');
        $this->assertDatabaseHas('subscribers', [
            'email' => 'reader@example.com',
        ]);
    }

    public function test_subscribing_with_duplicate_email_does_not_create_duplicate_record(): void
    {
        Subscriber::create(['email' => 'reader@example.com']);

        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
        ]);

        $response->assertSessionHas('newsletter_success');
        $this->assertCount(1, Subscriber::where('email', 'reader@example.com')->get());
    }

    public function test_subscribing_with_invalid_email_fails_validation(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('subscribers', [
            'email' => 'not-an-email',
        ]);
    }

    public function test_json_request_receives_json_response(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'json.subscriber@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'json.subscriber@example.com',
        ]);
    }
}
