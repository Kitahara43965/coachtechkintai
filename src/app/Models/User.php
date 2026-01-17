<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BreakTime;
use App\Models\Timetable;
use App\Models\DraftTimetable;
use App\Models\DraftBreakTime;
use App\Constants\UserRole;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function setPasswordAttribute($value)
    {
        if (!Hash::needsRehash($value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function draftTimetables()
    {
        return $this->hasMany(DraftTimetable::class);
    }

    public function currentTimetable()
    {
        return $this->timetables()
            ->whereNull('checkout_at')
            ->orderByDesc('checkin_at')
            ->first();
    }

    public function currentBreakTime()
    {
        return BreakTime::whereNotNull('break_time_start_at')
            ->whereNull('break_time_end_at')
            ->whereHas('timetable', fn ($q) =>
                $q->where('user_id', $this->id)
                ->whereNull('checkout_at')
            )
            ->first();
    }

    public function currentDraftTimetables()
    {
        return $this->draftTimetables()
            ->whereNull('checkout_at')
            ->orderByDesc('checkin_at')
            ->get();
    }

    public function currentDraftBreakTimes()
    {
        return DraftBreakTime::whereNotNull('break_time_start_at')
            ->whereNull('break_time_end_at')
            ->whereHas('draftTimetable', fn ($q) =>
                $q->where('user_id', $this->id)
                ->whereNull('checkout_at')
            )
            ->get();
    }

    public function isCheckin(){

        $isCheckin = $this->currentTimetable() ? true : false;

        return($isCheckin);
    }

    public function isBreakTimeStart(){

        $isBreakTimeStart = $this->currentBreakTime() ? true : false;

        return($isBreakTimeStart);
    }

    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }
}