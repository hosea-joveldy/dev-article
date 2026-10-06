<x-guest-layout>
    <div class="mb-6">
        <h1 class="serif text-3xl font-normal mb-2">Reset password.</h1>
        <p class="text-sm text-stone-500">
            {{ __('Forgot your password? Enter your email address and we will email you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="pill w-full">
                {{ __('Email Reset Link') }}
            </button>
        </div>

        <div class="text-center text-xs text-stone-500 pt-3">
            <a href="{{ route('login') }}" class="underline text-stone-900 font-medium">Back to sign in</a>
        </div>
    </form>
</x-guest-layout>
