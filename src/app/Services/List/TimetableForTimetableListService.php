<?php

namespace App\Services\List;

use App\DTOs\TimetableList;
use App\Models\User;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class TimetableForTimetableListService
{
    public static function getTimetablesByDay(
        $restrictedUser,
        $orderKey,
        $order,
        $carbonTime,
        $carbonNow = null
    ){
        $timetables = null;

        $newCarbonNow = $carbonNow ? $carbonNow : Carbon::now();
        $timetableTableName = (new Timetable())->getTable();
        $validTimetableTableColumns = Schema::getColumnListing($timetableTableName);

        $candidateTimetablesQuery = Timetable::query();
        if($restrictedUser){
            $candidateTimetablesQuery = $candidateTimetablesQuery->where('timetables.user_id', $restrictedUser->id);
        }//$restrictedUser
        if ($order&& $orderKey && in_array($orderKey, $validTimetableTableColumns)) {
            $candidateTimetablesQuery = $candidateTimetablesQuery->orderBy($orderKey, $order);
        }//
        $candidateTimetables = $candidateTimetablesQuery->get();
        $maxCandidateTimetableNumber = 0;
        if($candidateTimetables&&!$candidateTimetables->isEmpty()){
            $maxCandidateTimetableNumber = $candidateTimetables->count();
        }//$candidateTimetables

        $carbonStartOfDay = $carbonTime->copy()->startOfDay();
        $carbonEndOfDay   = $carbonTime->copy()->endOfDay();

        for($loopTime=1;$loopTime<=2;$loopTime++){
            $timetableNumber = 0;
            for($candidateTimetableNumber=1;$candidateTimetableNumber<=$maxCandidateTimetableNumber;$candidateTimetableNumber++){
                $candidateTimetable = $candidateTimetables[$candidateTimetableNumber - 1];
                $candidateTimetableCheckinAt = null;
                $candidateTimetableCheckoutAt = null;
                if($candidateTimetable){
                    $candidateTimetableCheckinAt = $candidateTimetable->checkin_at;
                    $candidateTimetableCheckoutAt = $candidateTimetable->checkout_at;
                }//$candidateTimetable

                $checkinAt = null;
                $checkoutAt = null;
                if($candidateTimetableCheckinAt){
                    $checkinAt = $candidateTimetableCheckinAt;
                    if($candidateTimetableCheckoutAt){
                        $checkoutAt = $candidateTimetableCheckoutAt;
                    }else{//$candidateTimetableCheckoutAt
                        $checkoutAt = $newCarbonNow;
                    }//$candidateTimetableCheckoutAt
                }//$candidateTimetableCheckinAt

                $timetableNumberAdditionDenialMarker = 0;
                if($checkinAt&&$checkoutAt){
                    if($checkinAt -> gt($checkoutAt)){
                        $timetableNumberAdditionDenialMarker = 1;
                    }else{
                        if($checkoutAt->lt($carbonStartOfDay)){
                            $timetableNumberAdditionDenialMarker = 2;
                        }
                        if($checkinAt->gt($carbonEndOfDay)){
                            $timetableNumberAdditionDenialMarker = 3;
                        }
                    }
                }else{//$checkinAt
                    $timetableNumberAdditionDenialMarker = -1;
                }//$candidateTimetableCheckinAt
                if($timetableNumberAdditionDenialMarker === 0){
                    $timetableNumber = $timetableNumber + 1;
                    if($loopTime === 2){
                        $timetables[$timetableNumber - 1] = $candidateTimetable;
                    }//$loopTime
                }//$timetableNumberAdditionMarker&0

            }//$candidateTimetableNumber
            if($loopTime === 1){
                if($timetableNumber >= 1){
                    $timetables = array_fill(0,$timetableNumber,null);
                }//$timetableNumber&1
            }//$loopTime

        }//$loopTime

        return($timetables);
        
    }
}