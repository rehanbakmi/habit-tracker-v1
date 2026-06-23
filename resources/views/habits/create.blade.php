<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">
            Tambah Habit
        </h1>

        <form method="POST"
              action="{{ route('habits.store') }}">

            @csrf

            <div class="mb-4">

                <label class="block mb-2">
                    Judul Habit
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="border rounded w-full p-2"
                    required>

                @error('title')

                    <p class="text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    class="border rounded w-full p-2"
                    rows="4">{{ old('description') }}</textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Kategori
                </label>

                <select
                    name="category_id"
                    class="border rounded w-full p-2">

                    <option value="">
                        Pilih kategori
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

                @error('category_id')

                    <p class="text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <button
                type="submit"
                class="bg-green-500 text-white px-4 py-2 rounded">

                Simpan Habit

            </button>

        </form>

    </div>

</x-app-layout>