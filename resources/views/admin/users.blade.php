<x-app-layout>
    <div class="p-6 bg-gray-50 min-h-screen">

        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:underline text-sm">← Kembali</a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Kelola User</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Nama</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Jumlah Habit</th>
                        <th class="pb-3">Bergabung</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b last:border-0">
                            <td class="py-3 font-medium">{{ $user->name }}</td>
                            <td class="py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="py-3">{{ $user->habits_count }}</td>
                            <td class="py-3 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="py-3">
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
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
                            <td colspan="5" class="py-6 text-center text-gray-400">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
