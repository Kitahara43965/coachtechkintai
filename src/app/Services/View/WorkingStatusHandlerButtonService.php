<?php

namespace App\Services\View;
use Carbon\Carbon;
use App\Constants\CheckoutInfo;

class WorkingStatusHandlerButtonService
{
    public const ENVIRONMENT_STATUS_TAG = "勤務外";
    public const ENVIRONMENT_STATUS_CHECKIN_TAG = "出勤中";
    public const ENVIRONMENT_STATUS_CHECKIN_BREAK_TIME_START_TAG = "休憩中";
    public const ENVIRONMENT_STATUS_JOB_END_TAG = "退勤済";

    public const CHECKIN_BUTTON_TAG = "出勤";
    public const CHECKIN_BUTTON_CHECKIN_TAG = "退勤";
    public const CHECKIN_BUTTON_CHECKIN_BREAK_TIME_START_TAG = "退勤";

    public const BREAK_TIME_START_BUTTON_TAG = "休憩入";
    public const BREAK_TIME_START_BUTTON_CHECKIN_TAG = "休憩入";
    public const BREAK_TIME_START_BUTTON_CHECKIN_BREAK_TIME_START_TAG = "休憩戻";

    public const GOOD_JOB_TAG = "お疲れ様でした";

    public static function getWorkingStatusHandlerButtonProperties(
        $postedUser,
        $todayCompletedTimetableNumber
    ){
        $environmentStatusTag = self::ENVIRONMENT_STATUS_TAG;
        $environmentStatusCheckinTag = self::ENVIRONMENT_STATUS_CHECKIN_TAG;
        $environmentStatusCheckinBreakTimeStartTag = self::ENVIRONMENT_STATUS_CHECKIN_BREAK_TIME_START_TAG;
        $environmentStatusJobEndTag = self::ENVIRONMENT_STATUS_JOB_END_TAG;

        $checkinButtonTag = self::CHECKIN_BUTTON_TAG;
        $checkinButtonCheckinTag = self::CHECKIN_BUTTON_CHECKIN_TAG;
        $checkinButtonCheckinBreakTimeStartTag = self::CHECKIN_BUTTON_CHECKIN_BREAK_TIME_START_TAG;

        $breakTimeStartButtonTag = self::BREAK_TIME_START_BUTTON_TAG;
        $breakTimeStartButtonCheckinTag = self::BREAK_TIME_START_BUTTON_CHECKIN_TAG;
        $breakTimeStartButtonCheckinBreakTimeStartTag = self::BREAK_TIME_START_BUTTON_CHECKIN_BREAK_TIME_START_TAG;

        $currentGoodJobTag = self::GOOD_JOB_TAG;
        $isGoodJobVisible = false;

        $currentTimetable = null;
        $currentBreakTime = null;
        if($postedUser){
            $currentTimetable = $postedUser->currentTimetable();
            $currentBreakTime = $postedUser->currentBreakTime();
        }//$postedUser

        $isAllowedToToggleWorkingStatusHandlerButton = false;
        if($currentTimetable){
            $isAllowedToToggleWorkingStatusHandlerButton = true;
        }else{//$currentTimetable
            if(CheckoutInfo::IS_MAX_TODAY_COMPLETED_TIMETABLE_NUMBER === true){
                if($todayCompletedTimetableNumber < CheckoutInfo::MAX_TODAY_COMPLETED_TIMETABLE_NUMBER){
                    $isAllowedToToggleWorkingStatusHandlerButton = true;
                }//$todayCompletedTimetableNumber
            }else{
                $isAllowedToToggleWorkingStatusHandlerButton = true;
            }
        }//$currentTimetable


        $currentEnvironmentStatusTag = null;
        $currentCheckinButtonTag = null;
        $currentBreakTimeStartButtonTag = null;
        $isCheckinButtonVisible = false;
        $isBreakTimeStartButtonVisible = false;
        $isGoodJobVisible = false;

        if($isAllowedToToggleWorkingStatusHandlerButton === true){
            if($currentTimetable){
                if($currentBreakTime){
                    $currentEnvironmentStatusTag = $environmentStatusCheckinBreakTimeStartTag;
                    $currentCheckinButtonTag = $checkinButtonCheckinBreakTimeStartTag;
                    $currentBreakTimeStartButtonTag = $breakTimeStartButtonCheckinBreakTimeStartTag;
                    $isCheckinButtonVisible = false;
                    $isBreakTimeStartButtonVisible = true;
                    $isGoodJobVisible = false;
                }else{//$currentBreakTime
                    $currentEnvironmentStatusTag = $environmentStatusCheckinTag;
                    $currentCheckinButtonTag = $checkinButtonCheckinTag;
                    $currentBreakTimeStartButtonTag = $breakTimeStartButtonCheckinTag;
                    $isCheckinButtonVisible = true;
                    $isBreakTimeStartButtonVisible = true;
                    $isGoodJobVisible = false;
                }//$currentBreakTime
            }else{//$currentTimetable
                $currentEnvironmentStatusTag = $environmentStatusTag;
                $currentCheckinButtonTag = $checkinButtonTag;
                $currentBreakTimeStartButtonTag = $breakTimeStartButtonTag;
                $isCheckinButtonVisible = true;
                $isBreakTimeStartButtonVisible = false;
                $isGoodJobVisible = false;
            }//$currentTimetable
        }else{//$isAllowedToToggleWorkingStatusHandlerButton
            $currentEnvironmentStatusTag = $environmentStatusJobEndTag;
            $currentCheckinButtonTag = $checkinButtonTag;
            $currentBreakTimeStartButtonTag = $breakTimeStartButtonTag;
            $isCheckinButtonVisible = false;
            $isBreakTimeStartButtonVisible = false;
            $isGoodJobVisible = true;
        }//$isAllowedToToggleWorkingStatusHandlerButton

        $workingStatusHandlerButtonProperties = [
            "currentEnvironmentStatusTag" => $currentEnvironmentStatusTag,
            "currentCheckinButtonTag" => $currentCheckinButtonTag,
            "currentBreakTimeStartButtonTag" => $currentBreakTimeStartButtonTag,
            "isCheckinButtonVisible" => $isCheckinButtonVisible,
            "isBreakTimeStartButtonVisible" => $isBreakTimeStartButtonVisible,
            "currentGoodJobTag" => $currentGoodJobTag,
            "isGoodJobVisible" => $isGoodJobVisible,
        ];

        return($workingStatusHandlerButtonProperties);

    }//getToggleCssProperties
}