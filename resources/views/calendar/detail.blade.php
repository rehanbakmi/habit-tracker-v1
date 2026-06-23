<x-app-layout>

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="mb-6">
            <a href="{{ route('calendar.index') }}"
               class="text-indigo-600 hover:underline text-sm">
                ← Kembali ke Kalender
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $parsedDate->translatedFormat('l, d F Y') }}
            </h1>
        </div>

        {{-- LIST HABIT --}}
        @if($habits->isEmpty())
            <div class="bg-white rounded-2xl p-8 text-center shadow border border-gray-100">
                <p class="text-gray-400">Belum ada habit yang dibuat.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($habits as $habit)
                    @php $done = $habit->logs->isNotEmpty(); @endphp
                    <div class="bg-white rounded-2xl p-5 shadow border border-gray-100 flex items-center gap-4">

                        {{-- Status icon --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $done ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                            {{ $done ? '✓' : '✗' }}
                        </div>

                        <div class="flex-1">
                            <p class="font-bold text-gray-800">{{ $habit->title }}</p>
                            @if($habit->description)
                                <p class="text-sm text-gray-500 mt-0.5">{{ $habit->description }}</p>
                            @endif
                            @if($habit->category)
                                <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full mt-1 inline-block">
                                    {{ $habit->category->name }}
                                </span>
                            @endif
                        </div>

                        <div class="text-sm font-semibold {{ $done ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $done ? 'Selesai' : 'Terlewat' }}
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Ringkasan --}}
            @php
                $completedCount = $habits->filter(fn($h) => $h->logs->isNotEmpty())->count();
                $totalCount     = $habits->count();
                $percent        = round(($completedCount / $totalCount) * 100);
            @endphp
            <div class="mt-6 bg-white rounded-2xl p-5 shadow border border-gray-100">
                <p class="text-sm text-gray-500 mb-2">Penyelesaian hari ini</p>
                <div class="flex items-center gap-4">
                    <div class="flex-1 bg-gray-100 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all"
                             style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="font-bold text-gray-700">{{ $completedCount }}/{{ $totalCount }}</span>
                </div>
            </div>
        @endif

    </div>

</x-app-layout>