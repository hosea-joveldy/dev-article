<x-guest-layout>
    <div class="mb-6">
        <h1 class="serif text-3xl font-normal mb-2">Confirm password.</h1>
        <p class="text-sm text-stone-500">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="pill w-full">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
