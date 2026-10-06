<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-2xl">
                <div class="mb-8">
                    <h1 class="serif text-3xl font-normal text-[#191919]">Create Topic</h1>
                    <p class="mt-1 text-sm text-stone-500">Add a new topic for organizing stories.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error mb-6">
                        <ul class="list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nama" class="label-ruang">Topic Name *</label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama') }}"
                               required
                               maxlength="100"
                               class="input-ruang"
                               placeholder="e.g. Technology, Business, Design">
                        @error('nama')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-400">The URL slug will be generated automatically.</p>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-[#e5e5e5]">
                        <a href="{{ route('admin.categories.index') }}" class="pill-outline">
                            Cancel
                        </a>
                        <button type="submit" class="pill">
                            Save Topic
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>