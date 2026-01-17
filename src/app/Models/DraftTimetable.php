<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DraftBreakTime;

class DraftTimetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_admitted',
        'checkin_at',
        'checkout_at',
        'description',
    ];

    protected $casts = [
        'checkin_at'  => 'datetime',
        'checkout_at' => 'datetime',
        'is_admitted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function draftBreakTimes()
    {
        return $this->hasMany(DraftBreakTime::class, 'draft_timetable_id');
    }
}
