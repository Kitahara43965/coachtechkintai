<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Timetable;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'break_time_start_at',
        'break_time_end_at',
    ];

    protected $casts = [
        'break_time_start_at' => 'datetime',
        'break_time_end_at'   => 'datetime',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
}
