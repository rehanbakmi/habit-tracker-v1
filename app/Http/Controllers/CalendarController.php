<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1);
        $daysInMonth  = $startOfMonth->daysInMonth;

        $user   = auth()->user();
        $habits = $user->habits()->with('logs')->get();
        $total  = $habits->count();

        $calendarData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date      = Carbon::create($year, $month, $day)->toDateString();
            $completed = 0;

            foreach ($habits as $habit) {
                $done = $habit->logs->contains(function ($log) use ($date) {
                    return Carbon::parse($log->completed_date)->toDateString() === $date;
                });
                if ($done) {
                    $completed++;
                }
            }

            $calendarData[$date] = [
                'completed' => $completed,
                'total'     => $total,
                'percent'   => $total > 0 ? round(($completed / $total) * 100) : 0,
            ];
        }

        return view('calendar.index', compact(
            'calendarData', 'month', 'year',
            'daysInMonth', 'startOfMonth'
        ));
    }

    public function detail(Request $request, string $date)
    {
        $parsedDate = Carbon::parse($date);
        $user       = auth()->user();

        $habits = $user->habits()->with([
            'category',
            'logs' => function ($query) use ($date) {
                $query->whereDate('completed_date', $date);
            }
        ])->get();

        return view('calendar.detail', compact('habits', 'parsedDate'));
    }
}
