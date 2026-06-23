<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Category;

class HabitController extends Controller
{
    public function index()
    {
        $month = request('month', now()->month);
        $year  = request('year', now()->year);

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $habits = auth()->user()
                        ->habits()
                        ->with([
                            'category',
                            'logs' => function ($query) use ($month, $year) {
                                $query->whereMonth('completed_date', $month)
                                      ->whereYear('completed_date', $year);
                            }
                        ])
                        ->get()
                        ->groupBy(function ($habit) {
                            return $habit->category->name ?? 'Tanpa Kategori';
                        });

        return view('habits.index', compact(
            'habits',
            'month',
            'year',
            'daysInMonth'
        ));
    }

    public function create()
    {
        $categories = Category::all();

        return view('habits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        auth()->user()->habits()->create($validated);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil ditambahkan.');
    }

    public function complete(Habit $habit)
    {
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $habit->logs()->firstOrCreate([
            'completed_date' => Carbon::today(),
        ]);

        return back()->with('success', 'Habit berhasil diselesaikan hari ini.');
    }

    public function edit(Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $categories = Category::all();

        return view('habits.edit', compact('habit', 'categories'));
    }

    public function update(Request $request, Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|exists:categories,id', // ✅ ditambahkan
        ]);

        $habit->update($validated);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil diperbarui.');
    }

    public function destroy(Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $habit->delete();

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil dihapus.');
    }
}
