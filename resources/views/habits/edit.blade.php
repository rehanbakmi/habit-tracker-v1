<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">
            Edit Habit
        </h1>

        <form method="POST"
              action="{{ route('habits.update', $habit) }}">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="block mb-2">
                    Judul Habit
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $habit->title) }}"
                    class="border rounded w-full p-2"
                    required>

                @error('title')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="border rounded w-full p-2">{{ old('description', $habit->description) }}</textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Kategori
                </label>

                <select name="category_id" class="border rounded w-full p-2">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $habit->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded">

                Simpan Perubahan

            </button>

        </form>

    </div>

</x-app-layout>
