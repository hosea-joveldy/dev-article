<?php

namespace Tests\Feature;

use App\Mail\ThankYouForSubscribing;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_newsletter_and_receives_thank_you_email(): void
    {
        Mail::fake();

        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
        ]);

        $response->assertSessionHas('newsletter_success');
        $this->assertDatabaseHas('subscribers', [
            'email' => 'reader@example.com',
        ]);

        Mail::assertSent(ThankYouForSubscribing::class, function ($mail) {
            return $mail->hasTo('reader@example.com')
                && $mail->hasSubject('Thank you for subscribing to us');
        });
    }

    public function test_subscribing_with_duplicate_email_does_not_create_duplicate_or_email_again(): void
    {
        Subscriber::create(['email' => 'reader@example.com']);

        Mail::fake();

        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
        ]);

        $response->assertSessionHas('newsletter_success');
        $this->assertCount(1, Subscriber::where('email', 'reader@example.com')->get());

        // Ensure user is only emailed once
        Mail::assertNothingSent();
    }

    public function test_subscribing_with_invalid_email_fails_validation_and_sends_no_email(): void
    {
        Mail::fake();

        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('subscribers', [
            'email' => 'not-an-email',
        ]);

        Mail::assertNothingSent();
    }

    public function test_json_request_receives_json_response_and_sends_email(): void
    {
        Mail::fake();

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

        Mail::assertSent(ThankYouForSubscribing::class, 1);
    }

    public function test_thank_you_email_content_contains_expected_message(): void
    {
        $mailable = new ThankYouForSubscribing('reader@example.com');

        $mailable->assertHasSubject('Thank you for subscribing to us');
        $mailable->assertSeeInHtml('Thank you for subscribing to us');
        $mailable->assertSeeInHtml('reader@example.com');
    }
}
