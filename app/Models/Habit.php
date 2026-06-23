<?php

namespace App\Models;

use App\Models\HabitLog;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Habit extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    /**
     * Hitung streak berturut-turut.
     * Jika hari ini belum ditandai, streak tetap dihitung dari kemarin
     * agar tidak langsung reset ke 0 di tengah hari.
     */
    public function currentStreak(): int
    {
        $dates = $this->logs
            ->pluck('completed_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $today     = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // Mulai dari hari ini kalau sudah selesai, kalau belum mulai dari kemarin
        $startDate = Carbon::parse(
            in_array($today, $dates) ? $today : $yesterday
        );

        // Kalau log terbaru bukan hari ini atau kemarin, streak sudah putus
        if ($dates[0] < $yesterday) {
            return 0;
        }

        $streak      = 0;
        $currentDate = $startDate->copy();

        foreach ($dates as $date) {
            if ($date === $currentDate->toDateString()) {
                $streak++;
                $currentDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
