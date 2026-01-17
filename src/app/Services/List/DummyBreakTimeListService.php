<?php

namespace App\Services\List;

use App\Services\Time\TimeStringService;
use App\DTOs\BreakTimeList;
use App\DTOs\TimetableList;
use App\DTOs\DraftBreakTimeList;
use App\DTOs\DraftTimetableList;
use App\Models\Timetable;
use App\Models\BreakTime;
use App\Models\DraftTimetable;
use App\Models\DraftBreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Constants\TimetableListType;

class DummyBreakTimeListService
{
    public static function getCurrentDummyBreakTimeList(
        $timetableListType,
        $dummyBreakTimeList,
        $referencedAt = null,
        $carbonNow = null
    ):void{

        $resetCarbonNow = Carbon::now();
        if($carbonNow){
            $newCarbonNow = $carbonNow;
        }else{//$carbonNow
            $newCarbonNow = $resetCarbonNow;
        }//$carbonNow

        $isDummyBreakTimeIdExistence = false;
        $dummyBreakTimeId = null;
        $breakTime = null;
        $draftBreakTime = null;
        if($dummyBreakTimeList){
            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $isDummyBreakTimeIdExistence = $dummyBreakTimeList->is_break_time_id_existence;
                $dummyBreakTimeId = $dummyBreakTimeList->break_time_id;
                $breakTime = BreakTime::find($dummyBreakTimeId);
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $isDummyBreakTimeIdExistence = $dummyBreakTimeList->is_draft_break_time_id_existence;
                $dummyBreakTimeId = $dummyBreakTimeList->draft_break_time_id;
                $draftBreakTime = DraftBreakTime::find($dummyBreakTimeId);
            }//$timetableListType
        }//$dummyBreakTimeList

        $dummyBreakTimeBreakTimeStartAt = null;
        $dummyBreakTimeBreakTimeEndAt = null;
        if($isDummyBreakTimeIdExistence){
            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $dummyBreakTimeBreakTimeStartAt = $breakTime->break_time_start_at;
                $dummyBreakTimeBreakTimeEndAt = $breakTime->break_time_end_at;
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $dummyBreakTimeBreakTimeStartAt = $draftBreakTime->break_time_start_at;
                $dummyBreakTimeBreakTimeEndAt = $draftBreakTime->break_time_end_at;
            }//$timetableListType
        }//$isDummyBreakTimeIdExistence

        $breakTimeStartAt = null;
        $breakTimeEndAt = null;
        if($dummyBreakTimeBreakTimeStartAt){
            $breakTimeStartAt = $dummyBreakTimeBreakTimeStartAt;
            if($dummyBreakTimeBreakTimeEndAt){
                $breakTimeEndAt = $dummyBreakTimeBreakTimeEndAt;
            }else{
                $breakTimeEndAt = $newCarbonNow;
            }//$dummyBreakTimeBreakTimeEndAt
        }//$breakTimeBreakTimeStartAt


        $stringAttendanceDetailBreakTimeStartAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $dummyBreakTimeBreakTimeStartAt,
            $referencedAt
        );

        $stringAttendanceDetailBreakTimeEndAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $dummyBreakTimeBreakTimeEndAt,
            $referencedAt
        );

        if($dummyBreakTimeList){
            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $dummyBreakTimeList->break_time_start_at = $breakTimeStartAt;
                $dummyBreakTimeList->break_time_end_at = $breakTimeEndAt;
                $dummyBreakTimeList->string_attendance_detail_break_time_start_at = $stringAttendanceDetailBreakTimeStartAt;
                $dummyBreakTimeList->string_attendance_detail_break_time_end_at = $stringAttendanceDetailBreakTimeEndAt;
                $dummyBreakTimeList->updated_at = $newCarbonNow;
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $dummyBreakTimeList->break_time_start_at = $breakTimeStartAt;
                $dummyBreakTimeList->break_time_end_at = $breakTimeEndAt;
                $dummyBreakTimeList->string_attendance_detail_break_time_start_at = $stringAttendanceDetailBreakTimeStartAt;
                $dummyBreakTimeList->string_attendance_detail_break_time_end_at = $stringAttendanceDetailBreakTimeEndAt;
                $dummyBreakTimeList->updated_at = $newCarbonNow;
            }//$timetableListType

            
        }//$dummyBreakTimeList
        
    }//getBreakTimeListDurations

    public static function getAscendingDummyBreakTimeListsFromDummyTimetableList(
        $currentDummyTimetableList = null,
        $orderKey = null,
        $order = null,
        $carbonNow = null
    ){


        $dummyBreakTimeLists = null;

       if($carbonNow){
            $newCarbonNow = $carbonNow;
        }else{//$carbonNow
            $newCarbonNow = Carbon::now();
        }//$carbonNow
        $existingBreakTimes = null;

        if($order){
            if($order === 'desc'){
                $isDayDescending = true;
            }else{//$order
                $isDayDescending = false;
            }//$order
        }else{//$order
            $isDayDescending = false;
        }//$order

        $timetableListType = TimetableListType::UNDEFINED;
        if($currentDummyTimetableList instanceof TimetableList){
            $timetableListType = TimetableListType::TIMETABLE_LIST;
        }else if($currentDummyTimetableList instanceof DraftTimetableList){
            $timetableListType = TimetableListType::DRAFT_TIMETABLE_LIST;
        }//instanceof

        $referencedAt = null;
        $isDummyTimetableIdExistence = false;
        $dummyTimetableId = null;
        if($currentDummyTimetableList){
            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $referencedAt = $currentDummyTimetableList->referenced_at;
                $isDummyTimetableIdExistence = $currentDummyTimetableList->is_timetable_id_existence;
                $dummyTimetableId = $currentDummyTimetableList->timetable_id;
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $referencedAt = $currentDummyTimetableList->referenced_at;
                $isDummyTimetableIdExistence = $currentDummyTimetableList->is_draft_timetable_id_existence;
                $dummyTimetableId = $currentDummyTimetableList->draft_timetable_id;
            }//$timetableListType
        }//$currentDummyTimetableList

        $dummyBreakTimeTableName = null;
        if($timetableListType === TimetableListType::TIMETABLE_LIST){
            $dummyBreakTimeTableName = (new BreakTime())->getTable();
        }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
            $dummyBreakTimeTableName = (new DraftBreakTime())->getTable();
        }//$timetableListType

        $validDummyBreakTimeTableColumns = null;
        if($dummyBreakTimeTableName){
            $validDummyBreakTimeTableColumns = Schema::getColumnListing($dummyBreakTimeTableName);
        }//$dummyBreakTimeTableName

        $query =  null;
        $existingBreakTimes = null;
        $existingDraftBreakTimes = null;
            
        if($isDummyTimetableIdExistence) {
            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $query = BreakTime::where('timetable_id', $dummyTimetableId);
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $query = DraftBreakTime::where('draft_timetable_id', $dummyTimetableId);
            }//$timetableListType

            if($query&&$orderKey && $order && $validDummyBreakTimeTableColumns){
                if (in_array($orderKey, $validDummyBreakTimeTableColumns)) {
                    $query = $query->orderBy($orderKey, $order);
                }//$orderKey
            }//$query

            if($timetableListType === TimetableListType::TIMETABLE_LIST){
                $existingBreakTimes = $query? $query->get() : null;
            }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                $existingDraftBreakTimes = $query? $query->get() : null;
            }//$timetableListType

        }//$isDummyTimetableIdExistence


        $maxDummyBreakTimeNumber = 0;
        if($timetableListType === TimetableListType::TIMETABLE_LIST){
            if($existingBreakTimes && !$existingBreakTimes->isEmpty()){
                $maxDummyBreakTimeNumber = $existingBreakTimes->count();
            }//$existingBreakTimes
        }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
            if($existingDraftBreakTimes && !$existingDraftBreakTimes->isEmpty()){
                $maxDummyBreakTimeNumber = $existingDraftBreakTimes->count();
            }//
        }//$timetableListType

        
        for($loopTime=1;$loopTime<=2;$loopTime++){

            for($dummyBreakTimeNumber=1; $dummyBreakTimeNumber <= $maxDummyBreakTimeNumber; $dummyBreakTimeNumber++){

                $breakTime = null;
                $draftBreakTime = null;
                $id = 0;
                $dummyBreakTimeId = null;
                $isDummyBreakTimeIdExistence = false;

                if($timetableListType === TimetableListType::TIMETABLE_LIST){
                    $breakTime = $existingBreakTimes[$dummyBreakTimeNumber - 1];
                    $id = $dummyBreakTimeNumber;
                    $dummyBreakTimeId = $breakTime->id;
                    $isDummyBreakTimeIdExistence = true;
                }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                    $draftBreakTime = $existingDraftBreakTimes[$dummyBreakTimeNumber - 1];
                    $id = $dummyBreakTimeNumber;
                    $dummyBreakTimeId = $draftBreakTime->id;
                    $isDummyBreakTimeIdExistence = true;
                }//$breakTime

                $dummyBreakTimeList = null;
                if($loopTime === 2){
                    if($timetableListType === TimetableListType::TIMETABLE_LIST){
                        $dummyBreakTimeList = new BreakTimeList();
                        $dummyBreakTimeList->id = $id;
                        $dummyBreakTimeList->break_time_id = $dummyBreakTimeId;
                        $dummyBreakTimeList->is_break_time_id_existence = $isDummyBreakTimeIdExistence;
                        $dummyBreakTimeList->updated_at = $newCarbonNow;
                        $dummyBreakTimeList->created_at = $newCarbonNow;
                    }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                        $dummyBreakTimeList = new DraftBreakTimeList();
                        $dummyBreakTimeList->id = $id;
                        $dummyBreakTimeList->draft_break_time_id = $dummyBreakTimeId;
                        $dummyBreakTimeList->is_draft_break_time_id_existence = $isDummyBreakTimeIdExistence;
                        $dummyBreakTimeList->updated_at = $newCarbonNow;
                        $dummyBreakTimeList->created_at = $newCarbonNow;
                    }//$timetableListType

                    self::getCurrentDummyBreakTimeList($timetableListType,$dummyBreakTimeList,$referencedAt,$newCarbonNow);

                    $dummyBreakTimeLists[$dummyBreakTimeNumber - 1] = $dummyBreakTimeList;
                }//$loopTime
            }//$dummyBreakTimeNumber

            if($loopTime === 1){
                $dummyBreakTimeLists = null;
                if($maxDummyBreakTimeNumber >= 1){
                    $dummyBreakTimeLists = array_fill(0, $maxDummyBreakTimeNumber, null);
                }//$maxDummyBreakTimeNumber&1
            }//$loopTime
        }//$loopTime

        return($dummyBreakTimeLists);

    }//updateBreakTimeList

    
}
