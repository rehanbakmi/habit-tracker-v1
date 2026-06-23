<x-app-layout>

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">Daftar Habit</h1>
                <p class="text-gray-500 mt-1">
                    Pantau perkembangan kebiasaan Anda setiap hari.
                </p>
            </div>

            <a href="{{ route('habits.create') }}"
               class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl shadow-md transition">
                + Tambah Habit
            </a>

        </div>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 border border-green-200 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- NAVIGASI BULAN --}}
        <div class="bg-white/80 backdrop-blur shadow rounded-2xl p-4 mb-6 border border-gray-100">

            <div class="flex flex-wrap items-center justify-between gap-4">

                <a href="?month={{ $month == 1 ? 12 : $month - 1 }}&year={{ $month == 1 ? $year - 1 : $year }}"
                   class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition">
                    ← Sebelumnya
                </a>

                <h2 class="text-lg font-semibold text-gray-700">
                    {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                </h2>

                <a href="?month={{ $month == 12 ? 1 : $month + 1 }}&year={{ $month == 12 ? $year + 1 : $year }}"
                   class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition">
                    Berikutnya →
                </a>

            </div>
        </div>

        {{-- LEGEND --}}
        <div class="bg-white/80 backdrop-blur shadow rounded-2xl p-4 mb-8 border border-gray-100 flex flex-wrap gap-6 text-sm">

            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-green-500"></div>
                <span>Selesai</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-gray-200"></div>
                <span>Belum selesai</span>
            </div>

        </div>

        {{-- LIST HABIT --}}
        @forelse($habits as $categoryName => $categoryHabits)

            <div class="mb-10">

                {{-- CATEGORY --}}
                <h2 class="text-2xl font-bold text-gray-800 mb-5">
                    {{ $categoryName }}
                </h2>

                <div class="grid gap-6">

                    @foreach($categoryHabits as $habit)

                        @php
                            $today = today()->toDateString();
                        @endphp

                        {{-- CARD --}}
                        <div class="bg-white/90 backdrop-blur shadow-lg rounded-2xl p-6 border border-gray-100
                                    hover:-translate-y-1 transition duration-200">

                            {{-- TITLE --}}
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-800">
                                    {{ $habit->title }}
                                </h3>

                                @if($habit->description)
                                    <p class="text-gray-600 mt-2">
                                        {{ $habit->description }}
                                    </p>
                                @endif
                            </div>

                            {{-- STREAK --}}
                            <div class="mb-4">
                                <span class="inline-flex items-center bg-orange-50 text-orange-700
                                             px-3 py-1 rounded-xl border border-orange-200 font-semibold">
                                    🔥 Streak: {{ $habit->currentStreak() }} hari
                                </span>
                            </div>

                            {{-- HEATMAP --}}
                            <div class="mb-6 flex flex-wrap gap-1">

                                @for($day = 1; $day <= $daysInMonth; $day++)

                                    @php
                                        $date = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                                        $completed = $habit->logs->contains('completed_date', $date);
                                    @endphp

                                    <div title="{{ $date }}"
                                         class="w-6 h-6 rounded border
                                         {{ $completed ? 'bg-green-500 border-green-600' : 'bg-gray-200 border-gray-300' }}">
                                    </div>

                                @endfor

                            </div>

                            {{-- STATUS HARI INI --}}
                            <div class="mb-6">

                                @if($habit->logs->contains('completed_date', $today))

                                    <div class="inline-flex items-center gap-2 bg-green-50 text-green-700
                                                px-4 py-2 rounded-xl border border-green-200">

                                        ✓ Sudah selesai hari ini

                                    </div>

                                @else

                                    <form method="POST" action="{{ route('habits.complete', $habit) }}">
                                        @csrf

                                        <button type="submit"
                                                class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600
                                                       hover:from-green-600 hover:to-emerald-700 text-black px-5 py-2.5
                                                       rounded-xl shadow-md hover:shadow-lg transition active:scale-95">

                                            Tandai Selesai Hari Ini

                                        </button>

                                    </form>

                                @endif

                            </div>

                            {{-- ACTIONS --}}
                            <div class="flex flex-wrap gap-3">

                                <a href="{{ route('habits.edit', $habit) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl transition">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('habits.destroy', $habit) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus habit ini?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl transition">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>
            </div>

        @empty

            {{-- EMPTY STATE --}}
            <div class="bg-white/90 shadow rounded-2xl p-8 text-center border border-gray-100">

                <p class="text-gray-500 mb-4">
                    Belum ada habit yang ditambahkan.
                </p>

                <a href="{{ route('habits.create') }}"
                   class="inline-flex bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl shadow-md transition">
                    Tambah Habit Pertama
                </a>

            </div>

        @endforelse

    </div>

</x-app-layout>