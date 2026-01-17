<?php

namespace App\Services\List;

use App\DTOs\DraftTimetableList;
use App\Models\User;
use App\Models\DraftTimetable;

class DraftTimetableListToolService
{

    public static function getDraftTimetableListNumberFromDraftTimetableListId($draftTimetableListId,$draftTimetableLists=null){

        $maxDraftTimetableListNumber = 0;
        if($draftTimetableLists){
            $maxDraftTimetableListNumber = count($draftTimetableLists);
        }//$draftTimetableLists

        $searchedDraftTimetableListNumber = 0;
        if($draftTimetableListId !== null){
            for($i = 1;$i <= $maxDraftTimetableListNumber;$i++){
                $draftTimetableList = $draftTimetableLists[$i - 1];
                if($draftTimetableList->id === $draftTimetableListId){
                    $searchedDraftTimetableListNumber = $i;
                }//$draftTimetableListt->id
            }//$i
        }//$draftTimetableListId

        return($searchedDraftTimetableListNumber);

    }//getTimetableIdFromTimetableListNumber

    public static function getDraftTimetableListFromDraftTimetableListNumber($draftTimetableListNumber,$draftTimetableLists=null){
        
        $maxDraftTimetableListNumber = 0;
        if($draftTimetableLists){
            $maxDraftTimetableListNumber = count($draftTimetableLists);
        }//$draftTimetableLists

        if(!is_int($draftTimetableListNumber)){
            $draftTimetableListNumberInvalidMarker = -1;
        }else if($draftTimetableListNumber < 1){
            $draftTimetableListNumberInvalidMarker = 1;
        }else if($draftTimetableListNumber > $maxDraftTimetableListNumber){
            $draftTimetableListNumberInvalidMarker = 2;
        }else{//$draftTimetableListNumber
            $draftTimetableListNumberInvalidMarker = 0;
        }//$draftTimetableListNumber

        $draftTimetableList = null;
        if($draftTimetableListNumberInvalidMarker === 0){
            $draftTimetableList = $draftTimetableLists[$draftTimetableListNumber - 1];
        }//$draftTimetableListNumberInvalidMarker&0

        return($draftTimetableList);

    }//getTimetableFromTimetableListNumber

    public static function getDraftTimetableListDraftTimetableUserId($draftTimetableList){
        $draftTimetableListDraftTimetableId = $draftTimetableList ? $draftTimetableList->draft_timetable_id : null;
        $draftTimetableListDraftTimetable = DraftTimetable::find($draftTimetableListDraftTimetableId);
        $draftTimetableListDraftTimetableUser = $draftTimetableListDraftTimetable ? $draftTimetableListDraftTimetable->user : null;
        $draftTimetableListDraftTimetableUserId = $draftTimetableListDraftTimetableUser ? $draftTimetableListDraftTimetableUser->id : null;
        return($draftTimetableListDraftTimetableUserId);
    }

}