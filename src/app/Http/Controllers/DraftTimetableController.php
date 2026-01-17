<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DraftTimetableListRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Timetable;
use App\Models\BreakTime;
use App\Models\DraftTimetable;
use App\Models\DraftBreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\Time\OverlapService;
use App\Constants\TimetableOverlapCondition;
use App\Services\List\OverlappedDummyTimetableService;
use App\Services\View\DraftTimetableModalButtonService;

final class DraftTimetableFunctionKind{
    public const UNDEFINED = 0;
    public const MODAL = 1;
    public const SUBMIT = 2;
    public const UPDATE = 3;
    public const REPLACE = 4;
}//DraftTimetableFunctionKind

class DraftTimetableController extends Controller{

    public function addDraftTimetable($dummyTimetableListUser,$draftTimetableDTO,$draftBreakTimeDTOs){
        return DB::transaction(function () use ($dummyTimetableListUser,$draftTimetableDTO,$draftBreakTimeDTOs) {
            $maxDraftBreakTimeNumber = 0;
            if($draftBreakTimeDTOs){
                $maxDraftBreakTimeNumber = count($draftBreakTimeDTOs);
            }//$draftBreakTimeDTOs

            $draftTimetableAdditionDenialMarker = 0;
            if(!$dummyTimetableListUser){
                $draftTimetableAdditionDenialMarker = 1;
            }
            if(!$draftTimetableDTO){
                $draftTimetableAdditionDenialMarker = 2;
            }

            $changingDraftTimetable = null;
            if($draftTimetableAdditionDenialMarker === 0){
                $changingDraftTimetable = new DraftTimetable();
                $changingDraftTimetable->user_id = $dummyTimetableListUser->id;
                $changingDraftTimetable->is_admitted = false;
                $changingDraftTimetable->checkin_at = $draftTimetableDTO->checkin_at;
                $changingDraftTimetable->checkout_at = $draftTimetableDTO->checkout_at;
                $changingDraftTimetable->description = $draftTimetableDTO->description;
                $changingDraftTimetable->save();
                if($maxDraftBreakTimeNumber >= 1){
                    for($draftBreakTimeNumber=1;$draftBreakTimeNumber<=$maxDraftBreakTimeNumber;$draftBreakTimeNumber++){
                        $draftBreakTimeDTO = $draftBreakTimeDTOs[$draftBreakTimeNumber - 1];
                        $changingDraftBreakTime = new DraftBreakTime();
                        $changingDraftBreakTime->draft_timetable_id = $changingDraftTimetable->id;
                        $changingDraftBreakTime->break_time_start_at = $draftBreakTimeDTO->break_time_start_at;
                        $changingDraftBreakTime->break_time_end_at = $draftBreakTimeDTO->break_time_end_at;
                        $changingDraftBreakTime->save();
                    }//$draftBreakTimeNumber
                }//$maxDraftBreakTimeNumber&1
            }//$draftTimetableDTO
        });
    }//addDraftTimetable

    public function replaceTimetable(
        $postedUser,
        $currentDraftTimetableListDraftTimetableId,
        $draftTimetableDTO,
        $draftBreakTimeDTOs,
        $overlappedTimetables
    ){
        return DB::transaction(function () use (
            $postedUser,
            $currentDraftTimetableListDraftTimetableId,
            $draftTimetableDTO,
            $draftBreakTimeDTOs,
            $overlappedTimetables
        ) {

            $maxOverlappedTimetableNumber = 0;
            if($overlappedTimetables){
                $maxOverlappedTimetableNumber = count($overlappedTimetables);
            }//$overlappedTimetables

            for($overlappedTimetableNumber = 1; $overlappedTimetableNumber <= $maxOverlappedTimetableNumber;$overlappedTimetableNumber++){
                $overlappedTimetable = $overlappedTimetables[$overlappedTimetableNumber - 1];
                $overlappedTimetableId = $overlappedTimetable->id;
                $overlappedTimetableUser = $overlappedTimetable->user;
                $overlappedTimetableUser->timetables()->where('id', $overlappedTimetableId)->delete();
            }//$overlappedTimetableNumber

            $changingTimetable = null;
            if($postedUser){
                if($draftTimetableDTO){
                    $changingTimetable = new Timetable();
                    $changingTimetable->user_id = $postedUser->id;
                    $changingTimetable->checkin_at = $draftTimetableDTO->checkin_at;
                    $changingTimetable->checkout_at = $draftTimetableDTO->checkout_at;
                    $changingTimetable->description = $draftTimetableDTO->description;
                    $changingTimetable->save();

                    $maxDraftBreakTimeDTONumber = 0;
                    if($draftBreakTimeDTOs){
                        $maxDraftBreakTimeDTONumber = count($draftBreakTimeDTOs);
                    }//$draftBreakTimeDTOs

                    for($draftBreakTimeDTONumber=1;$draftBreakTimeDTONumber<=$maxDraftBreakTimeDTONumber;$draftBreakTimeDTONumber++){
                        $draftBreakTimeDTO = $draftBreakTimeDTOs[$draftBreakTimeDTONumber - 1];
                        if($draftBreakTimeDTO){
                            $changingBreakTime = new BreakTime();
                            $changingBreakTime->timetable_id = $changingTimetable->id;
                            $changingBreakTime->break_time_start_at = $draftBreakTimeDTO->break_time_start_at;
                            $changingBreakTime->break_time_end_at = $draftBreakTimeDTO->break_time_end_at;
                            $changingBreakTime->save();
                        }//$draftBreakTimeDTO
                    }//$draftBreakTimeDTONumber
                }//
            }//$overlappedTimetableId

            $currentDraftTimetableListDraftTimetable = DraftTimetable::find($currentDraftTimetableListDraftTimetableId);
            if($currentDraftTimetableListDraftTimetable){
                $currentDraftTimetableListDraftTimetable->is_admitted = true;
                $currentDraftTimetableListDraftTimetable->save();
            }//$currentDraftTimetableListDraftTimetable

        });
    }

    public function onCheckDraftTimetable(Request $request,$functionKind){
        $draftTimetableDTO = $request->draftTimetableDTO();
        $draftBreakTimeDTOs = $request->draftBreakTimeDTOs();
        $stringRequestCarbonNow = $request->stringRequestCarbonNow();
        $postedUserId = $request->postedUserId();
        $currentTimetableListTimetableId = $request->currentTimetableListTimetableId();
        $currentDraftTimetableListDraftTimetableId = $request->currentDraftTimetableListDraftTimetableId();
        $showFunctionKind = $request->showFunctionKind();
        $postedUser = User::find($postedUserId);
        $requestCarbonNow = Carbon::parse($stringRequestCarbonNow);

        $overlappedTimetables = OverlappedDummyTimetableService::getOverlappedTimetables(
            $postedUser, 
            $requestCarbonNow, 
            $draftTimetableDTO
        );
        $overlappedDraftTimetables = OverlappedDummyTimetableService::getOverlappedDraftTimetables(
            $postedUser, 
            $requestCarbonNow, 
            $draftTimetableDTO
        );

        if($functionKind === DraftTimetableFunctionKind::SUBMIT){
            $this->addDraftTimetable($postedUser,$draftTimetableDTO,$draftBreakTimeDTOs);
        }//$functionKind

        if($functionKind === DraftTimetableFunctionKind::REPLACE){
            $this->replaceTimetable(
                $postedUser,
                $currentDraftTimetableListDraftTimetableId,
                $draftTimetableDTO,
                $draftBreakTimeDTOs,
                $overlappedTimetables
            );
        }//$functionKind

        $fetchedStatus = "success";
        $jsCodeNumber = 200;

        $draftTimetableDTOCheckinAt = null;
        $draftTimetableDTOCheckoutAt = null;
        if($draftTimetableDTO){
            $draftTimetableDTOCheckinAt = $draftTimetableDTO->checkin_at;
            $draftTimetableDTOCheckoutAt = $draftTimetableDTO->checkout_at;
        }

        $draftTimetableModalButtonProperties = DraftTimetableModalButtonService::getDraftTimetableModalButtonProperties(
            $showFunctionKind,
            $currentDraftTimetableListDraftTimetableId,
        );

        $errors = null;
        $isDraftTimetableModalButtonVisible = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonVisible"];
        $isDraftTimetableModalButtonDisabled = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonDisabled"];
        $currentDraftTimetableModalButtonTag = $draftTimetableModalButtonProperties["currentDraftTimetableModalButtonTag"];
        $isDraftTimetableModalButtonMessageVisible = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonMessageVisible"];
        $currentDraftTimetableModalButtonMessageTag = $draftTimetableModalButtonProperties["currentDraftTimetableModalButtonMessageTag"];
        $isDisabledField = $draftTimetableModalButtonProperties["isDisabledField"];

        $results = [
            "overlappedTimetables" => $overlappedTimetables,
            "overlappedDraftTimetables" => $overlappedDraftTimetables,
            "fetchedStatus" => $fetchedStatus,
            "jsCodeNumber" => $jsCodeNumber,
            "draftTimetableDTO" => $draftTimetableDTO,
            "draftBreakTimeDTOs" => $draftBreakTimeDTOs,
            "errors" => $errors,
            "requestCarbonNow" => $requestCarbonNow,
            "isDraftTimetableModalButtonVisible" => $isDraftTimetableModalButtonVisible,
            "isDraftTimetableModalButtonDisabled" => $isDraftTimetableModalButtonDisabled,
            "currentDraftTimetableModalButtonTag" => $currentDraftTimetableModalButtonTag,
            "isDraftTimetableModalButtonMessageVisible" => $isDraftTimetableModalButtonMessageVisible,
            "currentDraftTimetableModalButtonMessageTag" => $currentDraftTimetableModalButtonMessageTag,
            "isDisabledField" => $isDisabledField,
        ];

        return($results);

    }//onCheckDraftTimetable

    //
    public function draftTimetableModal(DraftTimetableListRequest $request){
        $results = $this->onCheckDraftTimetable($request,DraftTimetableFunctionKind::MODAL);
        $jsCodeNumber = $results["jsCodeNumber"];
        return response()->json($results,$jsCodeNumber);
    }

    public function draftTimetableSubmit(DraftTimetableListRequest $request){
        $results = $this->onCheckDraftTimetable($request,DraftTimetableFunctionKind::SUBMIT);
        $jsCodeNumber = $results["jsCodeNumber"];
        return response()->json($results,$jsCodeNumber);
    }

    public function draftTimetableUpdate(DraftTimetableListRequest $request){
        $results = $this->onCheckDraftTimetable($request,DraftTimetableFunctionKind::UPDATE);
        $jsCodeNumber = $results["jsCodeNumber"];
        return response()->json($results,$jsCodeNumber);
    }

    public function draftTimetableReplace(DraftTimetableListRequest $request){
        $results = $this->onCheckDraftTimetable($request,DraftTimetableFunctionKind::REPLACE);
        $jsCodeNumber = $results["jsCodeNumber"];
        return response()->json($results,$jsCodeNumber);
    }

    
}
