<?php

namespace App\Services\Time;
use Carbon\Carbon;

class LocalizedCarbonService
{
    public static function create($year,$month,$day,$hour,$minute,$second,$stringTimeZone){
        $localizedCarbonTime = Carbon::create($year, $month, $day, $hour, $minute, $second, 'UTC');
        if($stringTimeZone){
            $localizedCarbonTime->timezone($stringTimeZone);
        }//$stringTimeZone
        return($localizedCarbonTime);
    }//getLocalizedCarbon
}