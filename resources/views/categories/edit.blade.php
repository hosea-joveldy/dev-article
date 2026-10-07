<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-2xl">
                <div class="mb-8">
                    <h1 class="serif text-3xl font-normal text-[#191919]">Edit Category</h1>
                    <p class="mt-1 text-sm text-stone-500">Update category name and URL slug.</p>
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

                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama" class="label-ruang">Category Name *</label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama', $category->nama) }}"
                               required
                               maxlength="100"
                               class="input-ruang">
                        @error('nama')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="label-ruang">Slug *</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug', $category->slug) }}"
                               required
                               maxlength="100"
                               class="input-ruang">
                        @error('slug')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-[#e5e5e5]">
                        <a href="{{ route('admin.categories.index') }}" class="pill-outline">
                            Cancel
                        </a>
                        <button type="submit" class="pill">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>