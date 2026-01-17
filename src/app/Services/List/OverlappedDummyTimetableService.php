<?php

namespace App\Services\List;

use App\Constants\TimetableType;
use App\Constants\TimetableOverlapCondition;
use App\Models\User;
use App\Models\Timetable;
use App\Models\DraftTimetable;
use Carbon\Carbon;
use App\Services\Time\OverlapService;
use App\Constants\CheckoutInfo;

class OverlappedDummyTimetableService{
        private static function checkOverlappedDummyTimetables(
        $timetableType, 
        $dummyTimetableListUser, 
        $requestCarbonNow, 
        $draftTimetableDTO
    ){

        $overlappedTimetables = null;
        $overlappedDraftTimetables = null;

        $draftTimetableDTOPanCheckinAt = $draftTimetableDTO->pan_checkin_at;
        $draftTimetableDTOPanCheckoutAt = $draftTimetableDTO->pan_checkout_at;
        $draftTimetableDTOPanCheckinAtStartOfDay = null;
        if($draftTimetableDTOPanCheckinAt){
            $draftTimetableDTOPanCheckinAtStartOfDay = $draftTimetableDTOPanCheckinAt->copy()->startOfDay();
        }//$draftTimetableDTOPanCheckinAt
        $draftTimetableDTOPanCheckoutAtEndOfDay = null;
        if($draftTimetableDTOPanCheckoutAt){
            $draftTimetableDTOPanCheckoutAtEndOfDay = $draftTimetableDTOPanCheckoutAt->copy()->endOfDay();
        }//$draftTimetableDTOPanCheckoutAt

        $searchDenialMarker = 0;
        if(!($dummyTimetableListUser instanceof User)){
            $searchDenialMarker = 1;
        }else if(!($requestCarbonNow instanceof Carbon)){
            $searchDenialMarker = 2;
        }else if(!($draftTimetableDTOPanCheckinAt instanceof Carbon)){
            $searchDenialMarker = 3;
        }else if(!($draftTimetableDTOPanCheckoutAt instanceof Carbon)){
            $searchDenialMarker = 4;
        }

        $timetables = null;
        $draftTimetables = null;
        
        if ($searchDenialMarker === 0) {

            $maxTimetableNumber = 0;
            if($timetableType === TimetableType::TIMETABLE){
                $timetables = Timetable::where('user_id', $dummyTimetableListUser->id)->get();
                if($timetables && !$timetables->isEmpty()){
                    $maxTimetableNumber = $timetables->count();
                }//$timetables
            }else if($timetableType === TimetableType::DRAFT_TIMETABLE){
                $draftTimetables = DraftTimetable::where('user_id', $dummyTimetableListUser->id)->get();
                if($draftTimetables && !$draftTimetables->isEmpty()){
                    $maxTimetableNumber = $draftTimetables->count();
                }//$draftTimetables
            }

            for($loopTime=1;$loopTime<=2;$loopTime++){
                $overlappedTimetableNumber = 0;

                for($timetableNumber=1;$timetableNumber<=$maxTimetableNumber;$timetableNumber++){
                    $timetableCheckinAt = null;
                    $timetableCheckoutAt = null;
                    $isAdmitted = false;
                    if($timetableType === TimetableType::TIMETABLE){
                        $timetable = $timetables[$timetableNumber - 1];
                        $timetableCheckinAt = $timetable->checkin_at;
                        $timetableCheckoutAt = $timetable->checkout_at;
                        $isAdmitted = false;
                    }else if($timetableType === TimetableType::DRAFT_TIMETABLE){
                        $draftTimetable = $draftTimetables[$timetableNumber - 1];
                        $timetableCheckinAt = $draftTimetable->checkin_at;
                        $timetableCheckoutAt = $draftTimetable->checkout_at;
                        $isAdmitted = $draftTimetable->is_admitted;
                    }//$timetableType

                    $timetablePanCheckinAt = null;
                    $timetablePanCheckoutAt = null;
                    if($timetableCheckinAt){
                        $timetablePanCheckinAt = $timetableCheckinAt;
                        if($timetableCheckoutAt){
                            $timetablePanCheckoutAt = $timetableCheckoutAt;
                        }else{//$timetableCheckoutAt
                            $timetablePanCheckoutAt = $requestCarbonNow;
                        }//$timetableCheckoutAt
                    }//$timetableCheckinAt

                    $isOverlapped = OverlapService::getIsOverlapped(
                        $draftTimetableDTOPanCheckinAtStartOfDay,
                        $draftTimetableDTOPanCheckoutAtEndOfDay,
                        $timetablePanCheckinAt,
                        $timetablePanCheckoutAt
                    );

                    if($isAdmitted === false && $isOverlapped === true){
                        $overlappedTimetableNumber = $overlappedTimetableNumber + 1;
                        if($loopTime === 2){
                            if($timetableType === TimetableType::TIMETABLE){
                                $overlappedTimetables[$overlappedTimetableNumber - 1] = $timetable;
                            }else if($timetableType === TimetableType::DRAFT_TIMETABLE){
                                $overlappedDraftTimetables[$overlappedTimetableNumber - 1] = $draftTimetable;
                            }//$timetableType
                        }//$loopTime
                    }//$isOverlapped
                }//$timetableNumber

                if($loopTime === 1){
                    if($timetableType === TimetableType::TIMETABLE){
                        $overlappedTimetables = array_fill(0,$overlappedTimetableNumber,null);
                    }else if($timetableType === TimetableType::DRAFT_TIMETABLE){
                        $overlappedDraftTimetables = array_fill(0,$overlappedTimetableNumber,null);
                    }//$timetableType
                }//$loopTime
            }//$loopTime
        }//$searchDenialMarker&0

        $results = [
            "overlappedTimetables" => $overlappedTimetables,
            "overlappedDraftTimetables" => $overlappedDraftTimetables,
        ];
        
        return $results;
    }

    public static function getOverlappedTimetables($dummyTimetableListUser, $requestCarbonNow, $draftTimetableDTO)
    {
        $results = OverlappedDummyTimetableService::checkOverlappedDummyTimetables(
            TimetableType::TIMETABLE,
            $dummyTimetableListUser, 
            $requestCarbonNow, 
            $draftTimetableDTO
        );

        $overlappedTimetables = $results["overlappedTimetables"];
        
        return($overlappedTimetables);
    }

    public static function getOverlappedDraftTimetables($dummyTimetableListUser, $requestCarbonNow, $draftTimetableDTO)
    {
        
        $results = OverlappedDummyTimetableService::checkOverlappedDummyTimetables(
            TimetableType::DRAFT_TIMETABLE,
            $dummyTimetableListUser, 
            $requestCarbonNow, 
            $draftTimetableDTO
        );

        $overlappedDraftTimetables = $results["overlappedDraftTimetables"];
        
        return($overlappedDraftTimetables);
    }
}