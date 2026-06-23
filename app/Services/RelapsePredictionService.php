<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitJournal;
use Illuminate\Support\Collection;

class RelapsePredictionService
{
    // Kata-kata negatif bahasa Indonesia
    private array $negativeWords = [
        'lelah', 'capek', 'malas', 'stress', 'stres', 'sedih',
        'gagal', 'menyerah', 'bosan', 'tidak bisa', 'susah',
        'payah', 'buruk', 'jelek', 'cape', 'burnt out', 'burnout',
    ];

    public function calculate(int $userId): array
    {
        $habits = Habit::where('user_id', $userId)->with('logs')->get();

        $streakScore   = $this->scoreFromStreaks($habits);
        $skipScore     = $this->scoreFromSkips($habits);
        $moodScore     = $this->scoreFromMood($userId);

        // Total skor 0–100, makin tinggi makin berisiko
        $totalScore = min(100, $streakScore + $skipScore + $moodScore);

        return [
            'score'      => $totalScore,
            'risk_level' => $this->riskLevel($totalScore),
            'breakdown'  => compact('streakScore', 'skipScore', 'moodScore'),
        ];
    }

    private function scoreFromStreaks(Collection $habits): int
    {
        if ($habits->isEmpty()) return 0;

        $avgStreak = $habits->avg(fn($h) => $h->currentStreak());

        // Streak pendek = risiko tinggi
        if ($avgStreak <= 1) return 40;
        if ($avgStreak <= 3) return 25;
        if ($avgStreak <= 7) return 10;
        return 0;
    }

    private function scoreFromSkips(Collection $habits): int
    {
        $totalSkips = 0;

        foreach ($habits as $habit) {
            $lastWeek = collect();
            for ($i = 1; $i <= 7; $i++) {
                $date = now()->subDays($i)->toDateString();
                $lastWeek->push($date);
            }

            $completed = $habit->logs
                ->pluck('completed_date')
                ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
                ->toArray();

            $skipped = $lastWeek->filter(fn($d) => !in_array($d, $completed))->count();
            $totalSkips += $skipped;
        }

        $avgSkip = $habits->count() > 0 ? $totalSkips / $habits->count() : 0;

        if ($avgSkip >= 5) return 40;
        if ($avgSkip >= 3) return 20;
        if ($avgSkip >= 1) return 10;
        return 0;
    }

   private function scoreFromMood(int $userId): int
   {
        $journal = HabitJournal::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();

        if (!$journal) return 0;

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders([
                    'x-internal-key' => env('SENTIMENT_API_KEY'),
                    'Content-Type'   => 'application/json',
                ])
                ->post(env('SENTIMENT_API_URL') . '/analyze', [
                    'text' => $journal->content,
                ]);

            if ($response->successful()) {
                return $response->json('mood_score'); // 0, 10, atau 20
            }
        } catch (\Exception $e) {
            // Kalau Python service mati, fallback ke keyword matching
        }

        // Fallback: keyword matching sederhana
        $text  = strtolower($journal->content);
        $found = 0;
        foreach ($this->negativeWords as $word) {
            if (str_contains($text, $word)) $found++;
        }
        if ($found >= 3) return 20;
        if ($found >= 1) return 10;
        return 0;
    }

    private function riskLevel(int $score): string
    {
        if ($score >= 60) return 'high';
        if ($score >= 30) return 'medium';
        return 'low';
    }
}