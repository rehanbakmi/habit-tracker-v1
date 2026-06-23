<x-app-layout>

    <div class="p-6">

        <h1 class="text-3xl font-bold mb-6">
            Dashboard Habit Tracker
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-indigo-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total Habit</p>
                <p class="text-3xl font-bold">{{ $totalHabits }}</p>
            </div>

            <div class="bg-green-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Selesai Hari Ini</p>
                <p class="text-3xl font-bold">{{ $completedToday }}</p>
            </div>

            <div class="bg-orange-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Streak Tertinggi</p>
                <p class="text-3xl font-bold">🔥 {{ $highestStreak }}</p>
            </div>

            <div class="bg-purple-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm">Total Checklist</p>
                <p class="text-3xl font-bold">{{ $totalChecklists }}</p>
            </div>

        </div>

        {{-- PREDIKSI RELAPSE --}}
        @php
            $color = match($prediction['risk_level']) {
                'high'   => 'red',
                'medium' => 'yellow',
                default  => 'green',
            };
            $label = match($prediction['risk_level']) {
                'high'   => '🔴 Risiko Tinggi — Aktifkan Mode Bertahan',
                'medium' => '🟡 Risiko Sedang — Tetap Perhatikan Pola Anda',
                default  => '🟢 Risiko Rendah — Pertahankan!',
            };
        @endphp

        <div class="mt-6 p-5 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50">
            <p class="font-bold text-{{ $color }}-800 text-lg">{{ $label }}</p>
            <p class="text-{{ $color }}-700 mt-1">Skor risiko: {{ $prediction['score'] }} / 100</p>
            <div class="mt-3 text-sm text-{{ $color }}-600 grid grid-cols-3 gap-2">
                <div>Streak: +{{ $prediction['breakdown']['streakScore'] }}</div>
                <div>Skip: +{{ $prediction['breakdown']['skipScore'] }}</div>
                <div>Mood: +{{ $prediction['breakdown']['moodScore'] }}</div>
            </div>
        </div>

        {{-- FORM JURNAL MOOD HARI INI --}}
        <div class="mt-6 bg-white p-5 rounded-xl shadow border border-gray-100">

            <h2 class="text-lg font-bold text-gray-800 mb-3">
                📝 Jurnal Mood Hari Ini
            </h2>

            @if(isset($todayJournal))
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 text-sm">
                    <p class="mb-2 text-gray-500">Sudah diisi hari ini:</p>
                    <p>{{ $todayJournal->content }}</p>
                </div>
                <form method="POST" action="{{ route('journal.update') }}" class="mt-3">
                    @csrf
                    @method('PUT')
                    <textarea name="content" rows="3"
                        class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="Perbarui jurnal hari ini...">{{ $todayJournal->content }}</textarea>
                    <button type="submit"
                        class="mt-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        Perbarui Jurnal
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('journal.store') }}">
                    @csrf
                    <textarea name="content" rows="3"
                        class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="Bagaimana perasaan Anda hari ini? Tuliskan di sini..."></textarea>
                    <button type="submit"
                        class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        Simpan Jurnal
                    </button>
                </form>
            @endif

        </div>

        <div class="mt-8">
            <a href="{{ route('habits.index') }}"
               class="bg-indigo-600 text-white px-5 py-3 rounded-lg">
                Kelola Habit
            </a>
        </div>

    </div>

</x-app-layout>