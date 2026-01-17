<?php

namespace App\Services\Time;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\DTOs\TimeStringParams;

class TimeStringService
{
    static function getTimeStringParamsFromSecond($hourMinuteSecond){

        $stringYear = null;
        $stringMonth = null;
        $stringDay = null;
        $stringWeekday = null;

        if($hourMinuteSecond){
            $minuteSecond = $hourMinuteSecond % 3600;
            $hour = ($hourMinuteSecond - $minuteSecond) / 3600;
            $second = $minuteSecond % 60;
            $minute = ($minuteSecond - $second) / 60;
        }else{//$hourMinuteSecond
            $minuteSecond = 0;
            $hour = 0;
            $minute = 0;
            $second = 0;
        }//$hourMinuteSecond

        $stringHour = sprintf("%d", $hour);

        $stringMinute = sprintf("%02d", $minute);
        $stringSecond = sprintf("%02d", $second);

        $timeStringParamsTime = new TimeStringParams();
        $timeStringParamsTime->is_time = true;
        $timeStringParamsTime->string_year = $stringYear;
        $timeStringParamsTime->string_month = $stringMonth;
        $timeStringParamsTime->string_day = $stringDay;
        $timeStringParamsTime->string_weekday = $stringWeekday;
        $timeStringParamsTime->string_hour = $stringHour;
        $timeStringParamsTime->string_minute = $stringMinute;
        $timeStringParamsTime->string_second = $stringSecond;

        return($timeStringParamsTime);
    }//getStringHourMinute

    public static function getStringHourMinuteFromSecond($hourMinuteSecond){
        $timeStringParamsTime = self::getTimeStringParamsFromSecond($hourMinuteSecond);
        $stringTime = $timeStringParamsTime->string_hour.":".$timeStringParamsTime->string_minute;
        return($stringTime);
    }//getStringHourMinuteFromSecond

    public static function getStringHourMinuteSecondFromSecond($hourMinuteSecond){
        $timeStringParamsTime = self::getTimeStringParamsFromSecond($hourMinuteSecond);
        $stringTime = $timeStringParamsTime->string_hour.":".$timeStringParamsTime->string_minute
            .":".$timeStringParamsTime->string_second;
        return($stringTime);
    }//getStringHourMinuteFromSecond


    public static function getTimeStringParamsFromCarbon($carbonTime = null){

        if($carbonTime){
            $time = strtotime($carbonTime);
            $year = date('Y', $time);
            $month = date('m', $time);
            $day = date('d', $time);
            $hour = date('H', $time);
            $minute = date('i', $time);
            $second = date('s', $time);
        }else{//$carbonTime
            $time = null;
            $year = 0;
            $month = 1;
            $day = 1;
            $hour = 0;
            $minute = 0;
            $second = 0;
        }//$carbonTime

        $newCarbonTime = Carbon::create($year, $month, $day,$hour,$minute,$second);

        if($year < 10000){
            $stringYear = sprintf("%04d", $year);
        }else{//$year
            $stringYear = sprintf("%d", $year);
        }//$year

        $stringMonth = sprintf("%02d", $month);
        $stringDay = sprintf("%02d", $day);
        Carbon::setLocale('ja');
        if($newCarbonTime){
            $stringWeekday = $newCarbonTime ->isoFormat('ddd');
        }else{//$newCarbonTime
            $stringWeekday = null;
        }//$newCarbonTime
        $stringHour = sprintf("%02d", $hour);
        $stringMinute = sprintf("%02d", $minute);
        $stringSecond = sprintf("%02d", $second);

        $timeStringParamsTime = new TimeStringParams();
        $timeStringParamsTime->is_time = true;
        $timeStringParamsTime->string_year = $stringYear;
        $timeStringParamsTime->string_month = $stringMonth;
        $timeStringParamsTime->string_day = $stringDay;
        $timeStringParamsTime->string_weekday = $stringWeekday;
        $timeStringParamsTime->string_hour = $stringHour;
        $timeStringParamsTime->string_minute = $stringMinute;
        $timeStringParamsTime->string_second = $stringSecond;

        return($timeStringParamsTime);

    }//getStringTimeLabelFromCarbon


    public static function getStringYearMonthDayWeekdayFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'/'.$timeStringParamsTime->string_month
            .'/'.$timeStringParamsTime->string_day.'('.$timeStringParamsTime->string_weekday.')';
        return($stringTime);
    }

    public static function getStringHourMinuteFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute;
        return($stringTime);
    }

    public static function getStringHourMinuteSecondFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute
            .':'.$timeStringParamsTime->string_second;
        return($stringTime);
    }

    public static function getStringYearMonthDayFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'/'.$timeStringParamsTime->string_month
            .'/'.$timeStringParamsTime->string_day;
        return($stringTime);
    }


    public static function getStringYearMonthDayWeekdayHourMinuteFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'/'.$timeStringParamsTime->string_month
            .'/'.$timeStringParamsTime->string_day.'('.$timeStringParamsTime->string_weekday.')'.chr(10)
            .$timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute;
        return($stringTime);
    }

    public static function getStringYearMonthDayWeekdayHourMinuteSecondFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'/'.$timeStringParamsTime->string_month
            .'/'.$timeStringParamsTime->string_day.'('.$timeStringParamsTime->string_weekday.')'.chr(10)
            .$timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute.':'.$timeStringParamsTime->string_second;
        return($stringTime);
    }

    public static function getStringLetteredYearFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'年';
        return($stringTime);
    }

    public static function getStringLetteredMonthDayFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_month.'月'.$timeStringParamsTime->string_day.'日';
        return($stringTime);
    }

    public static function getStringAttendanceDetailHourMinuteFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute;
        return($stringTime);
    }

    public static function getStringAttendanceDetailYearMonthDayHourMinuteFromCarbon($carbonTime = null){
        $timeStringParamsTime = self::getTimeStringParamsFromCarbon($carbonTime);
        $stringTime = $timeStringParamsTime->string_year.'/'.$timeStringParamsTime->string_month.'/'
            .$timeStringParamsTime->string_day.chr(32)
            .$timeStringParamsTime->string_hour.':'.$timeStringParamsTime->string_minute;
        return($stringTime);
    }

    public static function getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
        $carbonTime,
        $referencedTime = null
    ){

        $stringTime = null;
        if($carbonTime instanceof Carbon){
            if($referencedTime instanceof Carbon){
                if($carbonTime->isSameDay($referencedTime)){
                    $stringTime = TimeStringService::getStringAttendanceDetailHourMinuteFromCarbon($carbonTime);
                }else{//$carbonTime
                    $stringTime = TimeStringService::getStringAttendanceDetailYearMonthDayHourMinuteFromCarbon($carbonTime);
                }//$carbonTime
            }else{
                $stringTime = TimeStringService::getStringAttendanceDetailHourMinuteFromCarbon($carbonTime);
            }
        }

        return($stringTime);
    }

    public static function getStringAttendanceListTimeFromCarbonAndReferencedTime(
        $carbonTime,
        $referencedTime = null
    ){
        
        $stringTime = null;
        if($carbonTime instanceof Carbon){
            if($referencedTime instanceof Carbon){
                if($carbonTime->isSameDay($referencedTime)){
                    $stringTime = TimeStringService::getStringHourMinuteFromCarbon($carbonTime);
                }else{//$carbonTime
                    $stringTime = TimeStringService::getStringYearMonthDayWeekdayHourMinuteFromCarbon($carbonTime);
                }//$carbonTime
            }else{
                $stringTime = TimeStringService::getStringHourMinuteFromCarbon($carbonTime);
            }
        }
        return($stringTime);
    }


    public static function getStringStampCorrectionRequestListTimeFromCarbonAndReferencedTime(
        $carbonTime,
        $referencedTime = null
    ){
        $stringTime = null;
        if($carbonTime instanceof Carbon){
            if($referencedTime instanceof Carbon){
                if($carbonTime->isSameDay($referencedTime)){
                    $stringTime = TimeStringService::getStringYearMonthDayFromCarbon($carbonTime);
                }else{//$carbonTime
                    $stringTime = TimeStringService::getStringYearMonthDayFromCarbon($carbonTime);
                }//$carbonTime
            }else{
                $stringTime = TimeStringService::getStringYearMonthDayFromCarbon($carbonTime);
            }
        }
        return($stringTime);
    }

    

}
