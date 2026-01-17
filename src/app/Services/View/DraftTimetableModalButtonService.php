<?php

namespace App\Services\View;
use App\Models\DraftTimetable;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;

class DraftTimetableModalButtonService{
        public static function getDraftTimetableModalButtonProperties(
        $showFunctionKind,
        $currentDraftTimetableListDraftTimetableId,
    ){
        $currentDraftTimetable = DraftTimetable::find($currentDraftTimetableListDraftTimetableId);
        $currentDraftTimetableIsAdmitted = $currentDraftTimetable ? $currentDraftTimetable->is_admitted : false;

        $isDraftTimetableModalButtonVisible = false;
        $isDraftTimetableModalButtonDisabled = false;
        $currentDraftTimetableModalButtonTag = null;
        $isDraftTimetableModalButtonMessageVisible = false;
        $currentDraftTimetableModalButtonMessageTag = null;
        $isDisabledField = false;
        if($showFunctionKind === ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_ATTENDANCE_LIST){
            $isDraftTimetableModalButtonVisible = true;
            $isDraftTimetableModalButtonDisabled = false;
            $currentDraftTimetableModalButtonTag = "修正";
            $isDraftTimetableModalButtonMessageVisible = false;
            $currentDraftTimetableModalButtonMessageTag = null;
            $isDisabledField = false;
        }else if($showFunctionKind === ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
            if($currentDraftTimetableIsAdmitted === true){
                $isDraftTimetableModalButtonVisible = true;
                $isDraftTimetableModalButtonDisabled = true;
                $currentDraftTimetableModalButtonTag = "承認済み";
                $isDraftTimetableModalButtonMessageVisible = false;
                $currentDraftTimetableModalButtonMessageTag = null;
                $isDisabledField = true;
            }else{//$currentDraftTimetableIsAdmitted
                $isDraftTimetableModalButtonVisible = false;
                $isDraftTimetableModalButtonDisabled = true;
                $currentDraftTimetableModalButtonTag = "未承認";
                $isDraftTimetableModalButtonMessageVisible = true;
                $currentDraftTimetableModalButtonMessageTag = "*承認待ちのため修正はできません。";
                $isDisabledField = true;
            }//$currentDraftTimetableIsAdmitted
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_LIST){
            $isDraftTimetableModalButtonVisible = true;
            $isDraftTimetableModalButtonDisabled = false;
            $currentDraftTimetableModalButtonTag = "修正";
            $isDraftTimetableModalButtonMessageVisible = false;
            $currentDraftTimetableModalButtonMessageTag = null;
            $isDisabledField = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_STAFF_ID){
            $isDraftTimetableModalButtonVisible = true;
            $isDraftTimetableModalButtonDisabled = false;
            $currentDraftTimetableModalButtonTag = "修正";
            $isDraftTimetableModalButtonMessageVisible = false;
            $currentDraftTimetableModalButtonMessageTag = null;
            $isDisabledField = false;
        }else if($showFunctionKind === ShowFunctionKind::STAMP_CORRECTION_REQUEST_APPROVE_ATTENDANCE_CORRECT_REQUEST_ID){
            if($currentDraftTimetableIsAdmitted === true){
                $isDraftTimetableModalButtonVisible = true;
                $isDraftTimetableModalButtonDisabled = true;
                $currentDraftTimetableModalButtonTag = "承認済み";
                $isDraftTimetableModalButtonMessageVisible = false;
                $currentDraftTimetableModalButtonMessageTag = null;
                $isDisabledField = true;
            }else{//$currentDraftTimetableIsAdmitted
                $isDraftTimetableModalButtonVisible = true;
                $isDraftTimetableModalButtonDisabled = false;
                $currentDraftTimetableModalButtonTag = "承認";
                $isDraftTimetableModalButtonMessageVisible = false;
                $currentDraftTimetableModalButtonMessageTag = null;
                $isDisabledField = true;
            }//$currentDraftTimetableIsAdmitted
        }//$showFunctionKind

        $draftTimetableModalButtonProperties = [
            "isDraftTimetableModalButtonVisible" => $isDraftTimetableModalButtonVisible,
            "isDraftTimetableModalButtonDisabled" => $isDraftTimetableModalButtonDisabled,
            "currentDraftTimetableModalButtonTag" => $currentDraftTimetableModalButtonTag,
            "isDraftTimetableModalButtonMessageVisible" => $isDraftTimetableModalButtonMessageVisible,
            "currentDraftTimetableModalButtonMessageTag" => $currentDraftTimetableModalButtonMessageTag,
            "isDisabledField" => $isDisabledField,
        ];

        return($draftTimetableModalButtonProperties);
    }//getDraftTimetableModalButtonProperties
}