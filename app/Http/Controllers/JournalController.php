<?php

namespace App\Http\Controllers;

use App\Models\HabitJournal;
use App\Services\RelapsePredictionService;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        HabitJournal::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date'    => today(),
            ],
            [
                'content' => $request->content,
            ]
        );

        // Hitung ulang prediksi setelah jurnal disimpan
        $prediction = (new RelapsePredictionService())->calculate(auth()->id());

        // Simpan hasil ke jurnal hari ini
        HabitJournal::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->update([
                'relapse_score' => $prediction['score'],
                'risk_level'    => $prediction['risk_level'],
            ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jurnal disimpan. Skor risiko diperbarui.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        HabitJournal::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->update(['content' => $request->content]);

        // Hitung ulang prediksi setelah jurnal diperbarui
        $prediction = (new RelapsePredictionService())->calculate(auth()->id());

        HabitJournal::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->update([
                'relapse_score' => $prediction['score'],
                'risk_level'    => $prediction['risk_level'],
            ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jurnal diperbarui. Skor risiko diperbarui.');
    }
}