<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\List\DraftTimetableListValidation\DraftTimetableListValidationService;
use Carbon\Carbon;
use App\Services\View\DraftTimetableModalButtonService;

class DraftTimetableListRequest extends FormRequest
{
    protected $draftTimetableDTO = null;
    protected $draftBreakTimeDTOs = null;
    protected $stringRequestCarbonNow = null;

    public function draftTimetableDTO()
    {
        return $this->draftTimetableDTO;
    }

    public function draftBreakTimeDTOs()
    {
        return $this->draftBreakTimeDTOs;
    }

    public function stringRequestCarbonNow(){
        return $this->stringRequestCarbonNow;
    }

    public function postedUserId(){
        return $this->postedUserId;
    }

    public function currentTimetableListTimetableId(){
        return $this->currentTimetableListTimetableId;
    }

    public function currentDraftTimetableListDraftTimetableId(){
        return $this->currentDraftTimetableListDraftTimetableId;
    }

    public function showFunctionKind(){
        return $this->showFunctionKind;
    }
    

    public function rules(): array
    {
        return [
        ];
    }

    public function wantsJson()
    {
        return true;
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        // バリデーションエラーを取得
        $errors = $validator->errors()->messages();
        $overlappedTimetables = null;
        $overlappedDraftTimetables = null;
        $fetchedStatus = "error";
        $jsCodeNumber = 422;
        $draftTimetableDTO = null;
        $draftBreakTimeDTOs = null;
        $stringValueFields = $this->all();
        $stringISODateNow = $stringValueFields["stringISODateNow"];
        if($stringISODateNow){
            $requestCarbonNow = Carbon::parse($stringISODateNow);
        }else{
            $requestCarbonNow = Carbon::now();
        }

        $showFunctionKind = $stringValueFields["showFunctionKind"];
        $currentDraftTimetableListDraftTimetableId = $stringValueFields["currentDraftTimetableListDraftTimetableId"];

        $draftTimetableModalButtonProperties = DraftTimetableModalButtonService::getDraftTimetableModalButtonProperties(
            $showFunctionKind,
            $currentDraftTimetableListDraftTimetableId,
        );

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
            "errors" =>  $errors,
            "requestCarbonNow" => $requestCarbonNow,
            "isDraftTimetableModalButtonVisible" => $isDraftTimetableModalButtonVisible,
            "isDraftTimetableModalButtonDisabled" => $isDraftTimetableModalButtonDisabled,
            "currentDraftTimetableModalButtonTag" => $currentDraftTimetableModalButtonTag,
            "isDraftTimetableModalButtonMessageVisible" => $isDraftTimetableModalButtonMessageVisible,
            "currentDraftTimetableModalButtonMessageTag" => $currentDraftTimetableModalButtonMessageTag,
            "isDisabledField" => $isDisabledField,
        ];

        // JSON としてエラーを返す
        throw new \Illuminate\Validation\ValidationException($validator, response()->json($results, $jsCodeNumber));
    }


    public function withValidator($validator): void
    {
        $stringValueFields = $this->all();

        $stringId = $this->route('id');
        $id = $stringId ? (int)$stringId : null;

        $postedUserId = $stringValueFields["postedUserId"];
        $currentTimetableListTimetableId = $stringValueFields["currentTimetableListTimetableId"];
        $currentDraftTimetableListDraftTimetableId = $stringValueFields["currentDraftTimetableListDraftTimetableId"];
        $stringISODateNow = $stringValueFields["stringISODateNow"];
        $currentDummyTimetableListReferencedAt = $stringValueFields["currentDummyTimetableListReferencedAt"];
        $showFunctionKind = $stringValueFields["showFunctionKind"];

        if($stringISODateNow){
            $carbonNow = Carbon::parse($stringISODateNow);
        }else{
            $carbonNow = Carbon::now();
        }

        $referencedAt = null;
        if($currentDummyTimetableListReferencedAt){
            $referencedAt = Carbon::parse($currentDummyTimetableListReferencedAt);
        }

        $validator->after(function ($validator) use ($carbonNow,$stringValueFields,$referencedAt) {
            $validatorResults = DraftTimetableListValidationService::checkDraftTimetableListValidator($validator, $carbonNow,$stringValueFields,$referencedAt);
            $this->draftTimetableDTO = $validatorResults['draftTimetableDTO'];
            $this->draftBreakTimeDTOs = $validatorResults['draftBreakTimeDTOs'];
            $this->stringRequestCarbonNow = $carbonNow->toDateTimeString();
        });
    }
}
