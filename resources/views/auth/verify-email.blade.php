<x-guest-layout>
    <div class="mb-6">
        <h1 class="serif text-3xl font-normal mb-2">Verify email.</h1>
        <p class="text-sm text-stone-500">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent to you.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success mb-4 text-xs">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex items-center justify-between pt-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="pill text-xs">
                {{ __('Resend Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-xs text-stone-500 hover:text-stone-900">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
