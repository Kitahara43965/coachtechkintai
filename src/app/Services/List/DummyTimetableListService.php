<?php

namespace App\Services\List;

use App\Constants\TimetableListType;
use Carbon\Carbon;
use App\DTOs\TimetableList;
use App\DTOs\DraftTimetableList;
use App\Services\List\TimetableListService;
use App\Services\List\DraftTimetableListService;

class DummyTimetableListService{
    public static function getCurrentDummyTimetableListsFromDummyTimetableListArrays(
        $timetableListType,
        $dummyTimetableListArrays,
        $carbonNow
    ){
        $newCarbonNow = $carbonNow ? $carbonNow : Carbon::now();

        $maxDummyTimetableListNumber = 0;
        if($dummyTimetableListArrays){
           $maxDummyTimetableListNumber = count($dummyTimetableListArrays);
        }//$dummyTimetableListArrays

        $dummyTimetableLists = null;
        if($maxDummyTimetableListNumber >= 1){
            $dummyTimetableLists = array_fill(0,$maxDummyTimetableListNumber,null);
        }//$maxDummyTimetableListNumber¥

        for($dummyTimetableListNumber=1;$dummyTimetableListNumber<=$maxDummyTimetableListNumber;$dummyTimetableListNumber++){
            $dummyTimetableListArray = $dummyTimetableListArrays[$dummyTimetableListNumber - 1];
            $dummyTimetableList = null;
            if($dummyTimetableListArray){
                if($timetableListType === TimetableListType::TIMETABLE_LIST){
                    $dummyTimetableList = new TimetableList($dummyTimetableListArray);
                    TimetableListService::getCurrentTimetableList($dummyTimetableList,$carbonNow);
                }else if($timetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                    $dummyTimetableList = new DraftTimetableList($dummyTimetableListArray);
                    DraftTimetableListService::getCurrentDraftTimetableList($dummyTimetableList,$carbonNow);
                }//$timetableListType
            }//$dummyTimetableListArray
            
            $dummyTimetableLists[$dummyTimetableListNumber - 1] = $dummyTimetableList;
        }//$maxDummyTimetableListNumber

        return($dummyTimetableLists);

    }//etDummyTimetableListsFromDummyTimetableListArrays
}//DummyTimetableListService
