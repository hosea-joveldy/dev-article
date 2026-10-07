<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe an email to the newsletter.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => strtolower(trim($validated['email']))]
        );

        $message = $subscriber->wasRecentlyCreated
            ? 'Thank you! You have successfully subscribed to the Ruang newsletter.'
            : 'You are already subscribed to the Ruang newsletter.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('newsletter_success', $message);
    }
}
