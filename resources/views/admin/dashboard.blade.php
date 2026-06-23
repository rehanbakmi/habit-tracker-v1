<x-app-layout>
    <div class="p-6 bg-gray-50 min-h-screen">

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Panel Admin</h1>
        <p class="text-gray-500 mb-6">Selamat datang, {{ auth()->user()->name }}.</p>

        {{-- STATISTIK --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px" class="mb-8">

            <div class="bg-indigo-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total User</p>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>

            <div class="bg-green-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total Habit</p>
                <p class="text-3xl font-bold">{{ $totalHabits }}</p>
            </div>

            <div class="bg-orange-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total Log</p>
                <p class="text-3xl font-bold">{{ $totalLogs }}</p>
            </div>

            <div class="bg-purple-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total Kategori</p>
                <p class="text-3xl font-bold">{{ $totalCategory }}</p>
            </div>

        </div>

        {{-- USER TERBARU --}}
        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-700">User Terbaru</h2>
                <a href="{{ route('admin.users') }}"
                   class="text-sm text-indigo-600 hover:underline">Lihat semua →</a>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-2">Nama</th>
                        <th class="pb-2">Email</th>
                        <th class="pb-2">Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                        <tr class="border-b last:border-0">
                            <td class="py-3 font-medium">{{ $user->name }}</td>
                            <td class="py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="py-3 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- NAVIGASI ADMIN --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px" class="mt-6">
            <a href="{{ route('admin.users') }}"
               class="bg-white rounded-2xl shadow border border-gray-100 p-6 hover:border-indigo-300 transition">
                <p class="font-bold text-gray-700">👥 Kelola User</p>
                <p class="text-sm text-gray-400 mt-1">Lihat dan hapus akun pengguna</p>
            </a>
            <a href="{{ route('admin.categories') }}"
               class="bg-white rounded-2xl shadow border border-gray-100 p-6 hover:border-indigo-300 transition">
                <p class="font-bold text-gray-700">🏷️ Kelola Kategori</p>
                <p class="text-sm text-gray-400 mt-1">Tambah dan hapus kategori habit</p>
            </a>
        </div>

    </div>
</x-app-layout>
