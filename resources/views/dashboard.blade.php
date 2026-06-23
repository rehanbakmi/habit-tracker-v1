<x-app-layout>

    <div class="p-6 bg-gray-50 min-h-screen">

        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Dashboard Habit Tracker
        </h1>

        {{-- STATISTIK --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">

            <div style="background-color:#6366f1;color:white;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <p style="font-size:13px;opacity:0.9;">Total Habit</p>
                <p style="font-size:32px;font-weight:700;">{{ $totalHabits }}</p>
            </div>

            <div style="background-color:#22c55e;color:white;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <p style="font-size:13px;opacity:0.9;">Selesai Hari Ini</p>
                <p style="font-size:32px;font-weight:700;">{{ $completedToday }}</p>
            </div>

            <div style="background-color:#f97316;color:white;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <p style="font-size:13px;opacity:0.9;">Streak Tertinggi</p>
                <p style="font-size:32px;font-weight:700;">🔥 {{ $highestStreak }}</p>
            </div>

            <div style="background-color:#a855f7;color:white;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <p style="font-size:13px;opacity:0.9;">Total Checklist</p>
                <p style="font-size:32px;font-weight:700;">{{ $totalChecklists }}</p>
            </div>

        </div>

        {{-- PREDIKSI RELAPSE --}}
        @php
            $label = match($prediction['risk_level']) {
                'high'   => '🔴 Risiko Tinggi — Aktifkan Mode Bertahan',
                'medium' => '🟡 Risiko Sedang — Tetap Perhatikan Pola Anda',
                default  => '🟢 Risiko Rendah — Pertahankan!',
            };
            $bgColor     = match($prediction['risk_level']) {
                'high'   => '#fef2f2',
                'medium' => '#fefce8',
                default  => '#f0fdf4',
            };
            $borderColor = match($prediction['risk_level']) {
                'high'   => '#fca5a5',
                'medium' => '#fde047',
                default  => '#86efac',
            };
            $textColor   = match($prediction['risk_level']) {
                'high'   => '#991b1b',
                'medium' => '#854d0e',
                default  => '#166534',
            };
        @endphp

        <div style="margin-top:24px;padding:20px;border-radius:12px;
                    background-color:{{ $bgColor }};border:1px solid {{ $borderColor }};">
            <p style="font-weight:700;font-size:18px;color:{{ $textColor }};">{{ $label }}</p>
            <p style="color:{{ $textColor }};margin-top:4px;">Skor risiko: {{ $prediction['score'] }} / 100</p>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:12px;font-size:13px;color:{{ $textColor }};">
                <div>Streak: +{{ $prediction['breakdown']['streakScore'] }}</div>
                <div>Skip: +{{ $prediction['breakdown']['skipScore'] }}</div>
                <div>Mood: +{{ $prediction['breakdown']['moodScore'] }}</div>
            </div>
        </div>

        {{-- JURNAL MOOD --}}
        <div style="margin-top:24px;background:white;padding:20px;border-radius:12px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.06);border:1px solid #f3f4f6;">

            <h2 style="font-size:17px;font-weight:700;color:#1f2937;margin-bottom:12px;">
                📝 Jurnal Mood Hari Ini
            </h2>

            @if(isset($todayJournal))
                <div style="background:#f9fafb;padding:16px;border-radius:8px;font-size:14px;color:#374151;">
                    <p style="color:#6b7280;margin-bottom:6px;">Sudah diisi hari ini:</p>
                    <p>{{ $todayJournal->content }}</p>
                </div>
                <form method="POST" action="{{ route('journal.update') }}" style="margin-top:12px;">
                    @csrf
                    @method('PUT')
                    <textarea name="content" rows="3"
                        style="width:100%;border:1px solid #e5e7eb;border-radius:8px;padding:12px;
                               font-size:14px;outline:none;box-sizing:border-box;"
                        placeholder="Perbarui jurnal hari ini...">{{ $todayJournal->content }}</textarea>
                    <button type="submit"
                        style="margin-top:8px;background-color:#4b5563;color:white;
                               padding:8px 16px;border-radius:8px;font-size:14px;border:none;cursor:pointer;">
                        Perbarui Jurnal
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('journal.store') }}">
                    @csrf
                    <textarea name="content" rows="3"
                        style="width:100%;border:1px solid #e5e7eb;border-radius:8px;padding:12px;
                               font-size:14px;outline:none;box-sizing:border-box;"
                        placeholder="Bagaimana perasaan Anda hari ini? Tuliskan di sini..."></textarea>
                    <button type="submit"
                        style="margin-top:8px;background-color:#6366f1;color:white;
                               padding:8px 16px;border-radius:8px;font-size:14px;border:none;cursor:pointer;">
                        Simpan Jurnal
                    </button>
                </form>
            @endif

        </div>

        {{-- TOMBOL KELOLA HABIT --}}
        <div style="margin-top:32px;">
            <a href="{{ route('habits.index') }}"
               style="background-color:#6366f1;color:white;padding:12px 24px;
                      border-radius:8px;text-decoration:none;font-weight:600;">
                Kelola Habit
            </a>
        </div>

    </div>

</x-app-layout>