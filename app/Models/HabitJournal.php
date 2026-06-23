<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitJournal extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'content',
        'relapse_score',
        'risk_level',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}