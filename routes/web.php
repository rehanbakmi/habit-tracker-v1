<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitController;
use App\Services\RelapsePredictionService;
use App\Http\Controllers\JournalController;

Route::middleware('auth')->group(function () {
    Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
    Route::put('/journal', [JournalController::class, 'update'])->name('journal.update');
    Route::resource('habits', HabitController::class);
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])
    ->name('calendar.index');
    Route::get('/calendar/{date}', [App\Http\Controllers\CalendarController::class, 'detail'])
    ->name('calendar.detail');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('categories.destroy');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user      = auth()->user();
    $habits    = $user->habits()->with('logs')->get();
    $prediction = (new RelapsePredictionService())->calculate($user->id);

    $totalHabits     = $habits->count();
    $completedToday  = \App\Models\HabitLog::whereHas('habit', fn($q) => $q->where('user_id', $user->id))
                        ->whereDate('completed_date', today())->count();
    $totalChecklists = \App\Models\HabitLog::whereHas('habit', fn($q) => $q->where('user_id', $user->id))->count();
    $highestStreak   = $habits->map(fn($h) => $h->currentStreak())->max() ?? 0;

    $todayJournal = \App\Models\HabitJournal::where('user_id', $user->id)
        ->whereDate('date', today())
        ->first();

    return view('dashboard', compact(
        'totalHabits', 'completedToday', 'totalChecklists',
        'highestStreak', 'prediction', 'todayJournal'
    
        ));
})->middleware(['auth'])->name('dashboard');

Route::post(
    '/habits/{habit}/complete',
    [HabitController::class, 'complete']
)->name('habits.complete');

require __DIR__.'/auth.php';
