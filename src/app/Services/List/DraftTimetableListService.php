<?php

namespace App\Services\List;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\DraftTimetable;
use App\Models\User;
use App\DTOs\DraftTimetableList;
use App\Constants\Message;
use App\Services\Time\TimeStringService;
use Carbon\Carbon;
use App\Services\Time\LocalizedCarbonService;

class DraftTimetableListService
{

    public static function getCurrentDraftTimetableList($draftTimetableList,$carbonNow){

        $resetCarbonNow = Carbon::now();
        if($carbonNow){
            $newCarbonNow = $carbonNow;
        }else{//$carbonNow
            $newCarbonNow = $resetCarbonNow;
        }//$carbonNow

        $draftTimetableCheckinAt = null;
        $draftTimetableCheckoutAt = null;
        $draftTimetableCreatedAt = null;
        $referencedAt = null;

        if($draftTimetableList){
            $draftTimetableId = $draftTimetableList->draft_timetable_id;
            $draftTimetable = DraftTimetable::find($draftTimetableId);
            $draftTimetableCheckinAt = $draftTimetable->checkin_at;
            $draftTimetableCheckoutAt = $draftTimetable->checkout_at;
            $draftTimetableCreatedAt = $draftTimetable->created_at;

            $year = $draftTimetableCheckinAt->year;
            $month = $draftTimetableCheckinAt->month;
            $day = $draftTimetableCheckinAt->day;

            $referencedAt = LocalizedCarbonService::create($year, $month, $day, 0, 0, 0, 'Asia/Tokyo');

        }//$draftTimetableList

        $checkinAt = null;
        $checkoutAt = null;
        $isCheckinCheckoutChanging = false;
        if($draftTimetableCheckinAt){
            $checkinAt = $draftTimetableCheckinAt;
            if($draftTimetableCheckoutAt){
                $checkoutAt = $draftTimetableCheckoutAt;
            }else{//$draftTimetableCheckoutAt
                $checkoutAt = $newCarbonNow;
                $isCheckinCheckoutChanging = true;
            }//$draftTimetableCheckoutAt
        }//$draftTimetableCheckinAt


        $stringReferencedAtYearMonthDayWeekday = null;
        $stringReferencedAtLetteredYear = null;
        $stringReferencedAtLetteredMonthDay = null;
        if($referencedAt){
            $stringReferencedAtYearMonthDayWeekday = TimeStringService::getStringYearMonthDayWeekdayFromCarbon($referencedAt);
            $stringReferencedAtLetteredYear = TimeStringService::getStringLetteredYearFromCarbon($referencedAt);
            $stringReferencedAtLetteredMonthDay = TimeStringService::getStringLetteredMonthDayFromCarbon($referencedAt);
        }//$referencedAt


        $stringAttendanceDetailCheckinAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $draftTimetableCheckinAt,
            $referencedAt
        );

        $stringAttendanceDetailCheckoutAt = TimeStringService::getStringAttendanceDetailTimeFromCarbonAndReferencedTime(
            $draftTimetableCheckoutAt,
            $referencedAt
        );


        $stringStampCorrectionRequestListCreatedAt = TimeStringService::getStringStampCorrectionRequestListTimeFromCarbonAndReferencedTime(
            $draftTimetableCreatedAt,
            $referencedAt
        );

        $stringStampCorrectionRequestListCheckinAt = TimeStringService::getStringStampCorrectionRequestListTimeFromCarbonAndReferencedTime(
            $checkinAt,
            $referencedAt
        );

        $stringStampCorrectionRequestListCheckoutAt = TimeStringService::getStringStampCorrectionRequestListTimeFromCarbonAndReferencedTime(
            $checkoutAt,
            $referencedAt
        );

        $stringStampCorrectionRequestListCheckinCheckout = null;
        if($checkinAt&&$checkoutAt){
            if($checkinAt->isSameDay($checkoutAt)){
                $stringStampCorrectionRequestListCheckinCheckout = $stringStampCorrectionRequestListCheckinAt;
            }else{//
                $stringStampCorrectionRequestListCheckinCheckout 
                    = $stringStampCorrectionRequestListCheckinAt." ~ ".chr(10).$stringStampCorrectionRequestListCheckoutAt;
            }
        }//$draftTimetableCheckinAt&$draftTimetableCheckoutAt
        
        if($draftTimetableList){
            $draftTimetableList->checkin_at = $checkinAt;
            $draftTimetableList->checkout_at = $checkoutAt;
            $draftTimetableList->draft_timetable_created_at = $draftTimetableCreatedAt;
            $draftTimetableList->string_stamp_correction_request_list_checkin_checkout = $stringStampCorrectionRequestListCheckinCheckout;
            $draftTimetableList->string_stamp_correction_request_list_created_at = $stringStampCorrectionRequestListCreatedAt;
            $draftTimetableList->referenced_at = $referencedAt;
            $draftTimetableList->string_attendance_detail_checkin_at = $stringAttendanceDetailCheckinAt;
            $draftTimetableList->string_attendance_detail_checkout_at = $stringAttendanceDetailCheckoutAt;
            $draftTimetableList->string_referenced_at_year_month_day_weekday = $stringReferencedAtYearMonthDayWeekday;
            $draftTimetableList->string_referenced_at_lettered_year = $stringReferencedAtLetteredYear;
            $draftTimetableList->string_referenced_at_lettered_month_day = $stringReferencedAtLetteredMonthDay;
        }//$draftTimetableList

    }//getDraftTimetableListDurations
    

    public static function getAscendingDraftTimetableListsFromUser(
        $restrictedUser = null,
        $stampCorrectionRequestListIsAdmitted = false,
        $orderKey = null,
        $order = null,
        $carbonNow = null
    ){
        $newCarbonNow = $carbonNow ? $carbonNow : Carbon::now();
        $existingDraftTimetablesQuery = DraftTimetable::query();

        if($restrictedUser){
            $existingDraftTimetablesQuery = $existingDraftTimetablesQuery
                                            ->where('draft_timetables.user_id', $restrictedUser->id);
        }//$restrictedUser

        $draftTimetableTableName = (new DraftTimetable())->getTable(); // BreakTimeモデルのテーブル名を取得
        $validDraftTimetableTableColumns = Schema::getColumnListing($draftTimetableTableName); // テーブルのカラム名を自動で取得
        if ($order && $orderKey && in_array($orderKey, $validDraftTimetableTableColumns)) {
            $existingDraftTimetablesQuery = $existingDraftTimetablesQuery->orderBy($orderKey, $order);
        }//$orderKey
        $existingDraftTimetables = $existingDraftTimetablesQuery->get();
        $maxDraftTimetableNumber = 0;
        if( $existingDraftTimetables){
            if($existingDraftTimetables && !$existingDraftTimetables->isEmpty()){
                $maxDraftTimetableNumber = $existingDraftTimetables->count();
            }//$existingDraftTimetables
        }// $existingDraftTimetables
        $draftTimetableLists = null;

        for($loopTime = 1; $loopTime <=2; $loopTime++){
            $draftTimetableListNumber = 0;
            for($draftTimetableNumber = 1;$draftTimetableNumber <= $maxDraftTimetableNumber; $draftTimetableNumber++){
                $draftTimetable = $existingDraftTimetables[$draftTimetableNumber - 1];
                $isDraftTimetableIdExistence = true;
                $draftTimetableId = $draftTimetable->id;
                $draftTimetableIsAdmitted = $draftTimetable->is_admitted;
                $stringDraftTimetableDescription = $draftTimetable->description;

                $draftTimetableListNumberAdditionMarker = 0;
                if($draftTimetableIsAdmitted === $stampCorrectionRequestListIsAdmitted){
                    $draftTimetableListNumberAdditionMarker = 1;
                }//$draftTimetableIsAdmitted

                if($draftTimetableIsAdmitted === true){
                    $stringIsAdmitted = Message::STRING_IS_DRAFT_TIMETABLE_ADMITTED;
                }else{//$draftTimetableIsAdmitted
                    $stringIsAdmitted = Message::STRING_IS_DRAFT_TIMETABLE_NOT_ADMITTED;
                }//$draftTimetableIsAdmitted
                $draftTimetable = DraftTimetable::find($draftTimetableId);
                $draftTimetableUserId = $draftTimetable ? $draftTimetable->user_id : null;
                $draftTimetableUser = User::find($draftTimetableUserId);
                $stringDraftTimetableUserName = $draftTimetableUser ? $draftTimetableUser->name : null;

                if($draftTimetableListNumberAdditionMarker !== 0){
                    $draftTimetableListNumber = $draftTimetableListNumber + 1;
                    if($loopTime === 2){
                        $draftTimetableList = new DraftTimetableList();
                        $draftTimetableList->id = $draftTimetableListNumber;
                        $draftTimetableList->draft_timetable_id = $draftTimetableId;
                        $draftTimetableList->is_draft_timetable_id_existence = $isDraftTimetableIdExistence;
                        $draftTimetableList->draft_timetable_is_admitted = $draftTimetableIsAdmitted;
                        $draftTimetableList->string_draft_timetable_description = $stringDraftTimetableDescription;
                        $draftTimetableList->string_is_admitted = $stringIsAdmitted;
                        $draftTimetableList->string_draft_timetable_user_name = $stringDraftTimetableUserName;
                        $draftTimetableList->updated_at = $newCarbonNow;
                        $draftTimetableList->created_at = $newCarbonNow;
                        self::getCurrentDraftTimetableList($draftTimetableList,$newCarbonNow);
                        $draftTimetableLists[$draftTimetableListNumber - 1] = $draftTimetableList;
                    }//$loopTime
                }//$draftTimetableListNumberAdditionMarker&0
            }//for-$draftTimetableNumber
            if($loopTime === 1){
                if($draftTimetableListNumber >= 1){
                    $draftTimetableLists = array_fill(0, $draftTimetableListNumber, null);
                }//$draftTimetableListNumber
            }//$loopTime
        }//$loopTime
        return($draftTimetableLists);
    }//getAscendingBreakTimeListsFromUser


}
