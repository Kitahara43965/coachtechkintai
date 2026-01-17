<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Timetable;
use App\Models\BreakTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\View\WorkingStatusHandlerButtonService;
use App\Constants\CheckoutInfo;

class TimetableController extends Controller
{
    public const ENVIRONMENT_MANAGE_KIND = 1;
    public const SHIFT_ATTENDANCE_UPDATE_MANAGE_KIND = 2;
    public const CHECKIN_MANAGE_KIND = 3;
    public const BREAK_TIME_START_MANAGE_KIND = 4;

    public static function getTodayCompletedTimetableNumber($postedUser, $newCarbonNow){
        $postedUserTimetablesQuery = Timetable::query();

        if ($postedUser) {
            $postedUserTimetablesQuery = $postedUserTimetablesQuery->where('timetables.user_id', $postedUser->id);
        }

        // 今日の日付で、checkin_at と checkout_at が両方とも設定されているタイムテーブルを取得
        $postedUserTimetablesQuery = $postedUserTimetablesQuery
            ->whereDate('checkout_at', '=', $newCarbonNow->toDateString()) // checkout_at が今日の日付
            ->whereNotNull('checkin_at')  // checkin_at がnullでない
            ->whereNotNull('checkout_at'); // checkout_at がnullでない

        $postedUserTimetables = $postedUserTimetablesQuery->get();

        $todayCompletedTimetableNumber = 0;

        // タイムテーブルが存在すればカウントを取得
        if ($postedUserTimetables->isNotEmpty()) {
            $todayCompletedTimetableNumber = $postedUserTimetables->count();
        }

        return $todayCompletedTimetableNumber;
    }

    public function onManage($manageKind,$postedUserId,$newCarbonNow)
    {
        return DB::transaction(function () use ($manageKind,$postedUserId,$newCarbonNow) {
            $postedUser = null;
            if($postedUserId){
                $postedUser = User::where('id', $postedUserId)
                    ->lockForUpdate()
                    ->first();
            }//$postedUserId
            
            $isViewBack = false;
            $oldTodayCompletedTimetableNumber = self::getTodayCompletedTimetableNumber($postedUser, $newCarbonNow);
            if(CheckoutInfo::IS_MAX_TODAY_COMPLETED_TIMETABLE_NUMBER === true){
                if($oldTodayCompletedTimetableNumber >= CheckoutInfo::MAX_TODAY_COMPLETED_TIMETABLE_NUMBER){
                    $isViewBack = true;
                }//$oldTodayCompletedTimetableNumber
            }//CheckoutInfo::IS_MAX_TODAY_COMPLETED_TIMETABLE_NUMBER


            $undefinedToggleStatus = 0;
            $checkoutToggleStatus = 1;
            $checkinToggleStatus = 2;
            $breakTimeEndToggleStatus = 3;
            $breakTimeStartToggleStatus = 4;
            $postedUserExistenceToggleStatus = 5;
            $toggleStatus = $undefinedToggleStatus;

            $undefinedBreakTimeDataStatus = 0;
            $breakTimeStartBreakTimeDataStatus = 1;
            $breakTimeEndBreakTimeDataStatus = 2;
            $breakTimeDataStatus = $undefinedBreakTimeDataStatus;

            $compensatedCarbonNow = $newCarbonNow->copy()->setSecond(0);

            $changingCurrentTimetable = null;
            $changingCurrentBreakTime = null;
            $changingCurrentDraftTimetables = null;
            $changingCurrentDraftBreakTimes = null;
            if($postedUser){
                $changingCurrentTimetable = $postedUser->currentTimetable();
                $changingCurrentBreakTime = $postedUser->currentBreakTime();
                $changingCurrentDraftTimetables = $postedUser->currentDraftTimetables();
                $changingCurrentDraftBreakTimes = $postedUser->currentDraftBreakTimes();
            }//$postedUser

            $toggleStatus = $undefinedToggleStatus;
            if($postedUser){
                if($isViewBack === false){
                    if($manageKind == self::ENVIRONMENT_MANAGE_KIND){
                        $toggleStatus = $postedUserExistenceToggleStatus;
                    }else if($manageKind == self::SHIFT_ATTENDANCE_UPDATE_MANAGE_KIND){
                        $toggleStatus = $postedUserExistenceToggleStatus;
                    }else if($manageKind == self::CHECKIN_MANAGE_KIND){
                        if($changingCurrentTimetable){
                            $toggleStatus = $checkoutToggleStatus;
                        }else{//$changingCurrentTimetable
                            $toggleStatus = $checkinToggleStatus;
                        }//$changingCurrentTimetable
                    }else if($manageKind == self::BREAK_TIME_START_MANAGE_KIND){
                        if($changingCurrentTimetable){
                            if($changingCurrentBreakTime){
                                $toggleStatus = $breakTimeEndToggleStatus;
                            }else{//$changingCurrentBreakTime
                                $toggleStatus = $breakTimeStartToggleStatus;
                            }//$changingCurrentBreakTime
                        }//$changingCurrentTimetable
                    }//$manageKind
                }else{//$isViewBack
                    $toggleStatus = $postedUserExistenceToggleStatus;
                }//$isViewBack
            }//$postedUser

            $changingTimetable = null;
            $changingDraftTimetables = null;
            if($toggleStatus == $checkinToggleStatus){
                $changingTimetable = new Timetable();
                $changingTimetable->user_id = $postedUser->id;
                $changingTimetable->checkin_at = $compensatedCarbonNow;
                $changingDraftTimetables = null;
            }else if($toggleStatus == $checkoutToggleStatus){
                $changingTimetable = $changingCurrentTimetable;
                if($changingTimetable){
                    $changingTimetable->checkout_at = $compensatedCarbonNow;
                }//$changingTimetable
                $changingDraftTimetables = $changingCurrentDraftTimetables;
                if($changingDraftTimetables){
                    foreach($changingDraftTimetables as $changingDraftTimetable){
                        $changingDraftTimetable->checkout_at = $compensatedCarbonNow;
                    }//$changingDraftTimetables
                }//$changingDraftTimetables
            }else if($toggleStatus == $breakTimeStartToggleStatus){
                $changingTimetable = $changingCurrentTimetable;
            }else if($toggleStatus == $breakTimeEndToggleStatus){
                $changingTimetable = $changingCurrentTimetable;
            }else if($toggleStatus == $postedUserExistenceToggleStatus){
            }else{//$toggleStatus
            }//$toggleStatus

            if($changingTimetable){
                $changingTimetable->save();
                if($toggleStatus == $checkinToggleStatus){
                    $breakTimeDataStatus = $undefinedBreakTimeDataStatus;
                }else if($toggleStatus == $checkoutToggleStatus){
                    $breakTimeDataStatus = $breakTimeEndBreakTimeDataStatus;
                }else if($toggleStatus == $breakTimeStartToggleStatus){
                    $breakTimeDataStatus = $breakTimeStartBreakTimeDataStatus;
                }else if($toggleStatus == $breakTimeEndToggleStatus){
                    $breakTimeDataStatus = $breakTimeEndBreakTimeDataStatus;
                }else{//$toggleStatus
                    $breakTimeDataStatus = $undefinedBreakTimeDataStatus;
                }//$toggleStatus
            }else{//$changingTimetable
                $breakTimeDataStatus = $undefinedBreakTimeDataStatus;
            }//$changingTimetable

            if($changingDraftTimetables){
                foreach($changingDraftTimetables as $changingDraftTimetable){
                    $changingDraftTimetable->save();
                }//$changingDraftTimetables
            }//$changingDraftTimetables

            $changingBreakTime = null;
            $changingDraftBreakTimes = null;

            if($breakTimeDataStatus == $breakTimeStartBreakTimeDataStatus){
                $changingBreakTime = new BreakTime();
                $changingBreakTime->timetable_id = $changingTimetable->id;
                $changingBreakTime->break_time_start_at = $compensatedCarbonNow;
            }else if($breakTimeDataStatus == $breakTimeEndBreakTimeDataStatus){
                $changingBreakTime = $changingCurrentBreakTime;
                if($changingBreakTime){
                    $changingBreakTime->break_time_end_at = $compensatedCarbonNow;
                }//$changingBreakTime

                $changingDraftBreakTimes = $changingCurrentDraftBreakTimes;

                if($changingDraftBreakTimes){
                    foreach($changingDraftBreakTimes as $changingDraftBreakTime){
                        $changingDraftBreakTime->break_time_end_at = $compensatedCarbonNow;
                    }//$changingDraftBreakTimes
                }//$changingDraftBreakTimes

            }else{//$breakTimeDataStatus

            }//$breakTimeDataStatus

            if($changingBreakTime){
                $changingBreakTime->save();
            }//$changingBreakTime

            if($changingDraftBreakTimes){
                foreach($changingDraftBreakTimes as $changingDraftBreakTime){
                    $changingDraftBreakTime->save();
                }//$changingDraftBreakTimes
            }//$changingDraftBreakTimes

            if($postedUser){
                $postedUser->save();
            }//$postedUser

            if($toggleStatus == $undefinedToggleStatus){
                $jsCodeNumber = 401;
                $status = 'error';
                $message = 'User not found';
                $checkinAt = null;
            }else{//$toggleStatus
                $jsCodeNumber = 200;
                $status = 'success';
                $message = 'success';
                $checkinAt = $compensatedCarbonNow->format('H:i');
            }//$toggleStatus

            $todayCompletedTimetableNumber = self::getTodayCompletedTimetableNumber($postedUser, $newCarbonNow);

            $properties = WorkingStatusHandlerButtonService::getWorkingStatusHandlerButtonProperties(
                $postedUser,
                $todayCompletedTimetableNumber
            );
            $currentEnvironmentStatusTag = $properties['currentEnvironmentStatusTag'];
            $currentCheckinButtonTag = $properties['currentCheckinButtonTag'];
            $isCheckinButtonVisible = $properties['isCheckinButtonVisible'];
            $currentBreakTimeStartButtonTag = $properties['currentBreakTimeStartButtonTag'];
            $isBreakTimeStartButtonVisible = $properties['isBreakTimeStartButtonVisible'];

            $currentGoodJobTag = $properties['currentGoodJobTag'];
            $isGoodJobVisible = $properties['isGoodJobVisible'];

            $results=[
                'jsCodeNumber' => $jsCodeNumber,
                'status' => $status,
                'message' => $message,
                'todayCompletedTimetableNumber' => $todayCompletedTimetableNumber,
                'checkinAt' => $checkinAt,
                'currentEnvironmentStatusTag' => $currentEnvironmentStatusTag,
                'currentCheckinButtonTag' => $currentCheckinButtonTag,
                'isCheckinButtonVisible' => $isCheckinButtonVisible,
                'currentBreakTimeStartButtonTag' => $currentBreakTimeStartButtonTag,
                'isBreakTimeStartButtonVisible' => $isBreakTimeStartButtonVisible,
                'currentGoodJobTag' => $currentGoodJobTag,
                'isGoodJobVisible' => $isGoodJobVisible,
                'isViewBack' => $isViewBack,
            ];

            return($results);
        });

    }//onManage

    public function environment($postedUserId){
        $newCarbonNow = Carbon::now();
        $results = $this->onManage(self::ENVIRONMENT_MANAGE_KIND,$postedUserId,$newCarbonNow);
        return($results);
    }

    public function checkin(Request $request){
        $stringPostedUserId = $request->input('postedUserId');
        $stringISODateCurrentTime = $request->input("stringISODateCurrentTime");
        $newCarbonNow = $stringISODateCurrentTime ? Carbon::parse($stringISODateCurrentTime):Carbon::now();
        $results = $this->onManage(self::CHECKIN_MANAGE_KIND,(int)$stringPostedUserId,$newCarbonNow);
        $jsCodeNumber = $results['jsCodeNumber'];
        return response()->json($results,$jsCodeNumber);
    }

    public function breakTimeStart(Request $request){
        $stringPostedUserId = $request->input('postedUserId');
        $stringISODateCurrentTime = $request->input("stringISODateCurrentTime");
        $newCarbonNow = $stringISODateCurrentTime ? Carbon::parse($stringISODateCurrentTime):Carbon::now();
        $results = $this->onManage(self::BREAK_TIME_START_MANAGE_KIND,(int)$stringPostedUserId,$newCarbonNow);
        $jsCodeNumber = $results['jsCodeNumber'];
        return response()->json($results,$jsCodeNumber);
    }

}
