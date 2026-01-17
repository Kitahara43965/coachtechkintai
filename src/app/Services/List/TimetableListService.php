<?php

namespace App\Services\List;

use App\Models\User;
use App\Models\Timetable;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\DTOs\TimetableList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Services\Time\TimeStringService;
use App\Services\List\TimetableListToolService;
use App\Services\Time\LocalizedCarbonService;
use App\Constants\TimeLabel;

class TimetableListService
{
    public static function getCurrentTimetableList(
        $timetableList = null,
        $carbonNow = null
    ):void{
        
        $resetCarbonNow = Carbon::now();
        if($carbonNow){
            $newCarbonNow = $carbonNow;
        }else{//$carbonNow
            $newCarbonNow = $resetCarbonNow;
        }//$carbonNow

        $isTimetableIdExistence = false;
        $timetableId = null;
        $timetable = null;
        $referencedAt = null;
        if($timetableList){
            $isTimetableIdExistence = $timetableList->is_timetable_id_existence;
            $timetableId = $timetableList->timetable_id;
            $referencedAt = $timetableList->referenced_at;
            $timetable = Timetable::find($timetableId);
        }//$timetableList


        $timetableCheckinAt = null;
        $timetableCheckoutAt = null;
        
        if($isTimetableIdExistence){
            $timetableCheckinAt = $timetable->checkin_at;
            $timetableCheckoutAt = $timetable->checkout_at;
        }//$isTimetableIdExistence

        
        $totalWorkingAndBreakTimeSecond = 0;

        $checkinAt = null;
        $checkoutAt = null;
        if($timetableCheckinAt){
            $checkinAt = $timetableCheckinAt;
            if($timetableCheckoutAt){
                $checkoutAt = $timetable->checkout_at;
            }else{//$timetableCheckoutAt
                $checkoutAt = $newCarbonNow;
            }
            $totalWorkingAndBreakTimeSecond = $checkoutAt->diffInSeconds($checkinAt);
        }//$$timetableCheckinAt


        $breakTimes = null;
        if($isTimetableIdExistence){
            $breakTimes = $timetable->breakTimes;
        }//$isTimetableIdExistence

         $maxOriginalBreakTimeNumber = 0;
        if($breakTimes && !$breakTimes->isEmpty()){
            $maxOriginalBreakTimeNumber = $breakTimes->count();
        }//$breakTimes

        $totalBreakTimeSecond = 0;

        if($maxOriginalBreakTimeNumber >= 1){
            for($originalBreakTimeNumber=1;$originalBreakTimeNumber<=$maxOriginalBreakTimeNumber;$originalBreakTimeNumber++){
                $breakTime = $breakTimes[$originalBreakTimeNumber - 1];
                $breakTimeStartAt = $breakTime->break_time_start_at;
                
                $breakTimeEndAt = null;
                $maxBreakTimeSecond = 0;
                if ($breakTimeStartAt) {
                    if($breakTime->break_time_end_at){
                        $breakTimeEndAt = $breakTime->break_time_end_at;
                    }else{
                        $breakTimeEndAt = $newCarbonNow;
                    }
                    $maxBreakTimeSecond = $breakTimeEndAt->diffInSeconds($breakTimeStartAt);
                }//$breakTimeStartAt
                $totalBreakTimeSecond = $totalBreakTimeSecond + $maxBreakTimeSecond;

            }//$originalBreakTimeNumber
        }//$maxOriginalBreakTimeNumber&1

        $totalWorkingTimeSecond = $totalWorkingAndBreakTimeSecond - $totalBreakTimeSecond;

        $stringTotalBreakTimeMinute = null;
        $stringTotalWorkingTimeMinute = null;
        if($checkoutAt){
            $stringTotalBreakTimeMinute = TimeStringService::getStringHourMinuteFromSecond($totalBreakTimeSecond);
            $stringTotalWorkingTimeMinute = TimeStringService::getStringHourMinuteFromSecond($totalWorkingTimeSecond);
        }//$checkoutAt

        $stringReferencedAtYearMonthDayWeekday = null;
        $stringReferencedAtLetteredYear = null;
        $stringReferencedAtLetteredMonthDay = null;
        if($referencedAt){
            $stringReferencedAtYearMonthDayWeekday = TimeStringService::getStringYearMonthDayWeekdayFromCarbon($referencedAt);
            $stringReferencedAtLetteredYear = TimeStringService::getStringLetteredYearFromCarbon($referencedAt);
            $stringReferencedAtLetteredMonthDay = TimeStringService::getStringLetteredMonthDayFromCarbon($referencedAt);
        }//$referencedAt


        $stringCurrentTime = "";
        if($timetableCheckinAt && !$timetableCheckoutAt){
            $stringCurrentTime = " ".TimeLabel::STRING_CURRENT_TIME;
        }

        $stringAttendanceListCheckinAt = TimeStringService::getStringAttendanceListTimeFromCarbonAndReferencedTime(
            $checkinAt,
            $checkoutAt
        );

        $stringAttendanceListCheckoutAt = TimeStringService::getStringAttendanceListTimeFromCarbonAndReferencedTime(
            $checkoutAt,
            $checkinAt
        ).$stringCurrentTime;

        $stringAttendanceDetailCheckinAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $timetableCheckinAt,
            $referencedAt
        );

        $stringAttendanceDetailCheckoutAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $timetableCheckoutAt,
            $referencedAt
        );


        if($timetableList){
            $timetableList->checkin_at = $checkinAt;
            $timetableList->checkout_at =  $checkoutAt;
            $timetableList->total_break_time_second = $totalBreakTimeSecond;
            $timetableList->total_working_time_second = $totalWorkingTimeSecond;
            $timetableList->string_referenced_at_year_month_day_weekday = $stringReferencedAtYearMonthDayWeekday;
            $timetableList->string_referenced_at_lettered_year = $stringReferencedAtLetteredYear;
            $timetableList->string_referenced_at_lettered_month_day = $stringReferencedAtLetteredMonthDay;
            $timetableList->string_attendance_list_checkin_at = $stringAttendanceListCheckinAt;
            $timetableList->string_attendance_list_checkout_at = $stringAttendanceListCheckoutAt;
            $timetableList->string_attendance_detail_checkin_at = $stringAttendanceDetailCheckinAt;
            $timetableList->string_attendance_detail_checkout_at = $stringAttendanceDetailCheckoutAt;
            $timetableList->string_total_break_time_minute = $stringTotalBreakTimeMinute;
            $timetableList->string_total_working_time_minute = $stringTotalWorkingTimeMinute;
            $timetableList->updated_at = $newCarbonNow;
        }//$timetableList
        
    }//getNewTableLists


    public static function getAscendingTimetableLists(
        $carbonStartDay,
        $carbonEndDay,
        $restrictedUser = null,
        $orderKey = null,
        $order = null,
        $carbonNow = null,
        $isDayCompensation = false,
    ){
        $timetableLists = null;

        if($carbonNow){
            $newCarbonNow = $carbonNow;
        }else{//$carbonNow
            $newCarbonNow = Carbon::now();
        }//$carbonNow

        $IsCarbonDatesDenial = false;
        if(!($carbonStartDay instanceof Carbon)){
            $IsCarbonDatesDenial = true;
        }else if(!($carbonEndDay instanceof Carbon)){
            $IsCarbonDatesDenial = true;
        }else{
            if($carbonStartDay ->gt($carbonEndDay)){
                $IsCarbonDatesDenial = true;
            }
        }

        $carbonDates = null;
        if($IsCarbonDatesDenial === false){
            $carbonDates = CarbonPeriod::create($carbonStartDay, '1 day', $carbonEndDay);
        }//$IsCarbonDatesDenial

        if($order){
            if($order === 'desc'){
                $isDayDescending = true;
            }else{//$order
                $isDayDescending = false;
            }//$order
        }else{//$order
            $isDayDescending = false;
        }//$order

        if($carbonDates){
            if($isDayDescending){
                $carbonDateArrayElements = array_reverse($carbonDates->toArray());
            }else{//$isDayDescending
                $carbonDateArrayElements = $carbonDates->toArray();
            }//$isDayDescending
        }else{//$carbonDates
            $carbonDateArrayElements = null;
        }//$carbonDates
        
        $maxDayNumber = 0;
        if($carbonDateArrayElements){
            $maxDayNumber = count($carbonDateArrayElements);
        }//$carbonDateArrayElements

        $timetableTableName = (new Timetable())->getTable();
        $validTimetableTableColumns = Schema::getColumnListing($timetableTableName);

        for($loopTime=1;$loopTime<=2;$loopTime++){
            $totalTimetableListNumber = 0;
            $totalDayNumber = 0;
            $validTimetableTimetableListNumber = 0;

            for($dayNumber = 1;$dayNumber <= $maxDayNumber;$dayNumber++) {

                $carbonDateArrayElement = $carbonDateArrayElements[$dayNumber - 1];

                $existingTimetables = TimetableForTimetableListService::getTimetablesByDay(
                    $restrictedUser,
                    $orderKey,
                    $order,
                    $carbonDateArrayElement,
                    $newCarbonNow
                );

                $maxOriginalTimetableNumber = 0;
                if($existingTimetables){
                   $maxOriginalTimetableNumber = count($existingTimetables);
                }//$existingTimetables
                
                $isTimetableIdExistence = false;
                $maxTimetableListNumber = 0;
                if($maxOriginalTimetableNumber >= 1){
                    $isTimetableIdExistence = true;
                    $maxTimetableListNumber = $maxOriginalTimetableNumber;
                }else{//$maxOriginalTimetableNumber
                    $isTimetableIdExistence = false;
                    if($isDayCompensation === true){
                        $maxTimetableListNumber = 1;
                    }else{
                        $maxTimetableListNumber = 0;
                    }//$isDayCompensation
                }//$maxOriginalTimetableNumber

                $year = 0;
                $month = 1;
                $day = 1;
                if($carbonDateArrayElement){
                    $year = $carbonDateArrayElement->year;
                    $month = $carbonDateArrayElement->month;
                    $day = $carbonDateArrayElement->day;
                }//$carbonDateArrayElement

                $originalTimetableNumber = 0;

                for($timetableListNumber=1;$timetableListNumber<=$maxTimetableListNumber;$timetableListNumber++){
                    $wholeTimetableListNumber = $totalTimetableListNumber + $timetableListNumber;

                    $referencedAt = LocalizedCarbonService::create($year, $month, $day, 0, 0, 0,'Asia/Tokyo');

                    $timetable = null;
                    $timetableId = null;
                    $timetableUser = null;
                    $stringTimetableUserName = null;
                    $stringTimetableDescription = null;
                    if($maxOriginalTimetableNumber >= 1){
                        $validTimetableTimetableListNumber = $validTimetableTimetableListNumber + 1;
                        $originalTimetableNumber = $originalTimetableNumber + 1;
                        $timetable = $existingTimetables[$originalTimetableNumber - 1];
                        $timetableId = $timetable->id;
                        $timetableUser = $timetable->user;
                        $stringTimetableUserName = $timetableUser ? $timetableUser->name : null;
                        $stringTimetableDescription = $timetable->description;
                    }//$maxOriginalTimetableNumber&1

                    $id = 0;
                    if($maxOriginalTimetableNumber >= 1){
                        $id = $validTimetableTimetableListNumber;
                    }else{//$maxOriginalTimetableNumber
                        $id = -1 * $dayNumber;
                    }//$maxOriginalTimetableNumber

                    
                    if($loopTime === 2){
                        $timetableList = new TimetableList();
                        $timetableList->id = $id;
                        $timetableList->timetable_id = $timetableId;
                        $timetableList->is_timetable_id_existence = $isTimetableIdExistence;
                        $timetableList->string_timetable_user_name = $stringTimetableUserName;
                        $timetableList->string_timetable_description = $stringTimetableDescription;
                        $timetableList->day = $day;
                        $timetableList->referenced_at = $referencedAt;
                        $timetableList->updated_at = $newCarbonNow;
                        $timetableList->created_at = $newCarbonNow;

                        TimetableListService::getCurrentTimetableList($timetableList,$newCarbonNow);

                        $timetableLists[$wholeTimetableListNumber - 1] = $timetableList;
                    }//$loopTime
                }//$timetableListNumber

                $totalTimetableListNumber = $totalTimetableListNumber + $maxTimetableListNumber;
            }//endfor $dayNumber
            $totalDayNumber = $totalDayNumber + $maxDayNumber;
            
            if($loopTime === 1){
                        
                $timetableLists = null;
                if($totalTimetableListNumber >= 1){
                    $timetableLists = array_fill(0, $totalTimetableListNumber, null);
                }//$totalTimetableListNumber&1

            }//$loopTime
        }//endfor $loopTime
        
        return($timetableLists);

    }//getAscendingTimetable

}
