<x-app-layout>

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kalender Habit</h1>
                <p class="text-gray-500 mt-1">Klik tanggal untuk melihat detail habit hari itu.</p>
            </div>
        </div>

        {{-- NAVIGASI BULAN --}}
        <div class="bg-white shadow rounded-2xl p-4 mb-6 border border-gray-100">
            <div class="flex items-center justify-between">
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

        {{-- LEGENDA --}}
        <div class="flex gap-6 text-sm mb-4 px-1">
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-green-500"></div> Semua selesai</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-yellow-400"></div> Sebagian</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-gray-200"></div> Tidak ada</div>
        </div>

        {{-- GRID HARI --}}
        <div class="bg-white shadow rounded-2xl p-4 border border-gray-100">

            {{-- Header hari --}}
            <div class="grid grid-cols-7 mb-2">
                @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $dayName)
                    <div class="text-center text-xs font-semibold text-gray-400 py-2">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>

            {{-- Offset hari pertama --}}
            <div class="grid grid-cols-7 gap-1">

                @php $offset = $startOfMonth->dayOfWeek; @endphp
                @for($i = 0; $i < $offset; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date    = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                        $data    = $calendarData[$date];
                        $isToday = $date === today()->toDateString();

                        $bgColor = 'bg-gray-100 hover:bg-gray-200';
                        if ($data['total'] > 0) {
                            if ($data['percent'] === 100) {
                                $bgColor = 'bg-green-400 hover:bg-green-500 text-white';
                            } elseif ($data['percent'] > 0) {
                                $bgColor = 'bg-yellow-300 hover:bg-yellow-400';
                            }
                        }
                    @endphp

                    <a href="{{ route('calendar.detail', $date) }}"
                       class="rounded-xl p-2 text-center transition {{ $bgColor }}
                              {{ $isToday ? 'ring-2 ring-indigo-500' : '' }}">
                        <div class="text-sm font-bold {{ $isToday ? 'text-indigo-700' : '' }}">
                            {{ $day }}
                        </div>
                        @if($data['total'] > 0)
                            <div class="text-xs mt-1 opacity-80">
                                {{ $data['completed'] }}/{{ $data['total'] }}
                            </div>
                        @endif
                    </a>

                @endfor

            </div>
        </div>

    </div>

</x-app-layout>