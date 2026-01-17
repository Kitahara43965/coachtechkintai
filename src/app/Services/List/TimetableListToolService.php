<?php

namespace App\Services\List;

use App\DTOs\TimetableList;
use App\Models\User;
use App\Models\Timetable;

class TimetableListToolService
{

    public static function getTimetableListNumberFromTimetableListId($timetableListId,$timetableLists=null){

        $maxTimetableListNumber = 0;
        if($timetableLists){
            $maxTimetableListNumber = count($timetableLists);
        }//$timetableLists

        $searchedTimetableListNumber = 0;
        if($timetableListId !== null){
            for($i = 1;$i <= $maxTimetableListNumber;$i++){
                $timetableList = $timetableLists[$i - 1];
                if($timetableList->id === $timetableListId){
                    $searchedTimetableListNumber = $i;
                }//$timetableList->id
            }//$timetableListNumber
        }//$timetableListId

        return($searchedTimetableListNumber);

    }//getTimetableIdFromTimetableListNumber

        public static function getTimetableListFromTimetableListNumber($timetableListNumber,$timetableLists=null){
        
        $maxTimetableListNumber = 0;
        if($timetableLists){
            $maxTimetableListNumber = count($timetableLists);
        }//$timetableLists

        if(!is_int($timetableListNumber)){
            $timetableListNumberInvalidMarker = -1;
        }else if($timetableListNumber < 1){
            $timetableListNumberInvalidMarker = 1;
        }else if($timetableListNumber > $maxTimetableListNumber){
            $timetableListNumberInvalidMarker = 2;
        }else{//$timetableListNumber
            $timetableListNumberInvalidMarker = 0;
        }//$timetableListNumber

        $timetableList = null;
        if($timetableListNumberInvalidMarker === 0){
            $timetableList = $timetableLists[$timetableListNumber - 1];
        }//$timetableListNumberInvalidMarker&0

        return($timetableList);

    }//getTimetableFromTimetableListNumber


    public static function getTimetableListIdFromTimetableListNumber($timetableListNumber,$timetableLists=null){
        
        $timetableList = self::getTimetableListFromTimetableListNumber($timetableListNumber,$timetableLists);

        $timetableListId = null;
        if($timetableList){
            $timetableListId = $timetableList->id;
        }//$timetableList

        return($timetableListId);

    }//getTimetableFromCertainTimetableListNumber

    public static function getTimetableIdFromTimetableListNumber($timetableListNumber,$timetableLists=null){

        $timetableList = self::getTimetableListFromTimetableListNumber($timetableListNumber,$timetableLists);

        $timetableId = null;
        if($timetableList){
            $timetableId = $timetableList->timetable_id;
        }//$timetableList
        return($timetableId);

    }//getTimetableListIdFromTimetableListNumber


    public static function getTimetableListsFromJsonTimetableListArrays($timetableListArrays){

        $maxTimetableListNumber = 0;
        if($timetableListArrays){
           $maxTimetableListNumber = count($timetableListArrays);
        }//$timetableListArrays

        $timetableLists = null;
        if($maxTimetableListNumber >= 1){
            $timetableLists = array_fill(0, $maxTimetableListNumber, null);
        }//$maxTimetableListNumber&1

        if($maxTimetableListNumber >= 1){
            for($timetableListNumber=1;$timetableListNumber<=$maxTimetableListNumber;$timetableListNumber++){
                $timetableListArray = $timetableListArrays[$timetableListNumber - 1];
                $timetableLists[$timetableListNumber - 1] = new TimetableList($timetableListArray);
            }
        }//$maxTimetableListNumber
        return($timetableLists);
    }//getTimetableListFromJsonTimetableLists

    public static function getTimetableListFromJsonTimetableListArrays($timetableListArrays,$timetableListId){

        $timetableLists = self::getTimetableListsFromJsonTimetableListArrays($timetableListArrays);
        $timetableListNumber = self::getTimetableListNumberFromTimetableListId($timetableListId,$timetableLists);
        $timetableList = self::getTimetableListFromTimetableListNumber($timetableListNumber,$timetableLists);

        return($timetableList);

    }//getTimetableListFromJsonTimetableListArrays

    public static function getTimetableListTimetableUserId($timetableList){
        $timetableListTimetableId = $timetableList ? $timetableList->timetable_id : null;
        $timetableListTimetable = Timetable::find($timetableListTimetableId);
        $timetableListTimetableUser = $timetableListTimetable ? $timetableListTimetable->user : null;
        $timetableListTimetableUserId = $timetableListTimetableUser ? $timetableListTimetableUser->id : null;
        return($timetableListTimetableUserId);
    }


}
