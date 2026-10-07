<x-guest-layout>
    <div class="mb-6">
        <h1 class="serif text-3xl font-normal mb-2">Welcome back.</h1>
        <p class="text-sm text-stone-500">Sign in to explore stories and share your ideas.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#e5e5e5] text-[#191919] focus:ring-0" name="remember">
                <span class="ms-2 text-stone-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-stone-500 hover:text-stone-900" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="pill w-full">
                {{ __('Sign in') }}
            </button>
        </div>

        <div class="text-center text-xs text-stone-500 pt-3">
            No account yet? <a href="{{ route('register') }}" class="underline text-stone-900 font-medium">Create one</a>
        </div>
    </form>
</x-guest-layout>
