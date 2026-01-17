<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BreakTime;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'checkin_at',
        'checkout_at',
    ];

    protected $casts = [
        'checkin_at'    => 'datetime',
        'checkout_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // breakTimes テーブルとリレーション
    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class, 'timetable_id', 'id');
    }

    /**
     * @param \App\Models\User|null $user
     * @param \Carbon\Carbon|null $carbonTime
     */

    public function scopeByUserAndDate($query,$user = null,$carbonTime = null){
        if($carbonTime){
            $newCarbonTime = $carbonTime;
        }else{//$carbonTime
            $newCarbonTime = Carbon::now();
        }//$carbonTime

        if($user){
            $query = $query->where('user_id', $user->id);
        }//$user

        $query = $query->whereBetween(
                'checkin_at',
                [$newCarbonTime->copy()->startOfDay(), $newCarbonTime->copy()->endOfDay()]
            );

        return $query;
    }//scopeByUserAndDate
}
