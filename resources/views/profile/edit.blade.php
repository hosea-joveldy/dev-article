<x-app-layout>
    <x-slot name="header">
        <h1 class="serif text-3xl font-normal text-[#191919]">
            {{ __('Profile Settings') }}
        </h1>
        <p class="text-sm text-stone-500 mt-1">Manage your account information and preferences.</p>
    </x-slot>

    <div class="space-y-8 max-w-4xl mx-auto">
        <div class="p-6 sm:p-8 bg-white border border-[#e5e5e5] rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-[#e5e5e5] rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-[#e5e5e5] rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
