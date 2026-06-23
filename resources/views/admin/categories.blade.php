<x-app-layout>
    <div class="p-6 bg-gray-50 min-h-screen">

        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:underline text-sm">← Kembali</a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Kelola Kategori</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM TAMBAH KATEGORI --}}
        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 mb-6">
            <h2 class="font-bold text-gray-700 mb-3">Tambah Kategori Baru</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nama kategori..."
                       class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       required>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm transition">
                    Tambah
                </button>
            </form>
            @error('name')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- LIST KATEGORI --}}
        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Nama Kategori</th>
                        <th class="pb-3">Jumlah Habit</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-b last:border-0">
                            <td class="py-3 font-medium">{{ $category->name }}</td>
                            <td class="py-3">{{ $category->habits_count }}</td>
                            <td class="py-3">
                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:underline text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
