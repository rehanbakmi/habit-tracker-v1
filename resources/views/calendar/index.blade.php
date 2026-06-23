<x-app-layout>

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Kalender Habit</h1>
            <p class="text-gray-500 mt-1">Klik tanggal untuk melihat detail habit hari itu.</p>
        </div>

        {{-- NAVIGASI BULAN --}}
        <div class="bg-white shadow rounded-2xl p-4 mb-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <a href="?month={{ $month == 1 ? 12 : $month - 1 }}&year={{ $month == 1 ? $year - 1 : $year }}"
                   style="background-color:#f3f4f6;padding:8px 16px;border-radius:12px;">
                    ← Sebelumnya
                </a>
                <h2 class="text-lg font-semibold text-gray-700">
                    {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                </h2>
                <a href="?month={{ $month == 12 ? 1 : $month + 1 }}&year={{ $month == 12 ? $year + 1 : $year }}"
                   style="background-color:#f3f4f6;padding:8px 16px;border-radius:12px;">
                    Berikutnya →
                </a>
            </div>
        </div>

        {{-- LEGENDA --}}
        <div style="display:flex;gap:24px;margin-bottom:16px;font-size:14px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:16px;height:16px;border-radius:4px;background-color:#4ade80;"></div>
                Semua selesai
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:16px;height:16px;border-radius:4px;background-color:#fde047;"></div>
                Sebagian
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:16px;height:16px;border-radius:4px;background-color:#e5e7eb;"></div>
                Tidak ada
            </div>
        </div>

        {{-- GRID KALENDER --}}
        <div class="bg-white shadow rounded-2xl p-4 border border-gray-100">

            {{-- Header nama hari --}}
            <div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:8px;">
                @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $dayName)
                    <div style="text-align:center;font-size:12px;font-weight:600;color:#9ca3af;padding:8px 0;">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>

            {{-- Grid tanggal --}}
            <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;">

                {{-- Offset hari pertama --}}
                @php $offset = $startOfMonth->dayOfWeek; @endphp
                @for($i = 0; $i < $offset; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date    = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                        $data    = $calendarData[$date];
                        $isToday = $date === today()->toDateString();

                        // Tentukan warna background
                        if ($data['total'] > 0 && $data['percent'] === 100) {
                            $bg    = '#4ade80';
                            $color = 'white';
                        } elseif ($data['total'] > 0 && $data['percent'] > 0) {
                            $bg    = '#fde047';
                            $color = '#1f2937';
                        } else {
                            $bg    = '#f3f4f6';
                            $color = '#1f2937';
                        }

                        $outline = $isToday ? 'outline:2px solid #6366f1;' : '';
                    @endphp

                    <a href="{{ route('calendar.detail', $date) }}"
                       style="background-color:{{ $bg }};color:{{ $color }};{{ $outline }}
                              border-radius:12px;padding:8px 4px;text-align:center;
                              display:block;text-decoration:none;transition:opacity 0.2s;"
                       onmouseover="this.style.opacity='0.8'"
                       onmouseout="this.style.opacity='1'">

                        <div style="font-size:14px;font-weight:700;
                                    {{ $isToday ? 'color:#4338ca;' : '' }}">
                            {{ $day }}
                        </div>

                        @if($data['total'] > 0)
                            <div style="font-size:11px;margin-top:2px;opacity:0.85;">
                                {{ $data['completed'] }}/{{ $data['total'] }}
                            </div>
                        @endif

                    </a>

                @endfor

            </div>
        </div>

    </div>

</x-app-layout>