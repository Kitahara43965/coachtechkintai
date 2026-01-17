<?php

namespace App\Services\List\DraftTimetableListValidation;
use App\Services\List\DraftTimetableListValidation\åTimeFieldParamsService;
use App\Services\Time\TimeParseService;
use App\Services\Time\OverlapService;
use App\Constants\TimeParseErrorStatus;
use App\Constants\TimeFieldErrorStatus;
use App\Constants\TimeFieldPairErrorStatus;
use App\DTOs\TimeFieldParams;
use App\DTOs\TimeFieldPairParams;
use App\Constants\TimeFieldKind;
use App\Constants\TimeFieldPairKind;
use Carbon\Carbon;
use App\Constants\FieldType;

use App\DTOs\DraftTimetableDTO;
use App\DTOs\DraftBreakTimeDTO;

final class TimeFieldType{
    public const UNDEFINED = 0;
    public const TIME_FIELD = 1;
    public const TIME_FIELD_PAIR = 2;
    public const MAX = 2;
}


class DraftTimetableListValidationService{

    private static function setDraftTimetableListValidation(
        $validator,
        $timeFieldParamsCheckinAt,
        $timeFieldParamsCheckoutAt,
        $timeFieldParamsBreakTimeStartAts,
        $timeFieldParamsBreakTimeEndAts,
        $timeFieldParamsDescription,
        $timeFieldPairParamsCheckinCheckout,
        $timeFieldPairParamsBreakTimeStartBreakTimeEnds
    ):void{

        $checkinAtFieldKind = TimeFieldKind::CHECKIN_AT;
        $checkoutAtFieldKind = TimeFieldKind::CHECKOUT_AT;
        $breakTimeStartAtFieldKind = TimeFieldKind::BREAK_TIME_START_AT;
        $breakTimeEndAtFieldKind = TimeFieldKind::BREAK_TIME_END_AT;
        $descriptionFieldKind = TimeFieldKind::DESCRIPTION;

        $checkinCheckoutFieldKind = TimeFieldPairKind::CHECKIN_CHECKOUT;
        $breakTimeStartBreakTimeEndFieldKind = TimeFieldPairKind::BREAK_TIME_START_BREAK_TIME_END;

        $maxTimeFieldType = TimeFieldType::MAX;
        for($timeFieldType=1;$timeFieldType<=$maxTimeFieldType;$timeFieldType++){

            $stringFieldNames = null;
            if($timeFieldType === TimeFieldType::TIME_FIELD){
                $stringFieldNames = TimeFieldKind::fieldNames();
            }else if($timeFieldType === TimeFieldType::TIME_FIELD_PAIR){
                $stringFieldNames = TimeFieldPairKind::fieldNames();
            }//$timeFieldType
            $maxFieldKind = 0;
            if($stringFieldNames){
                $maxFieldKind = count($stringFieldNames);
            }//$stringFieldNames

            for($fieldKind=1;$fieldKind<=$maxFieldKind;$fieldKind++){
                $maxParamsValueNumber = 0;
                if($timeFieldType === TimeFieldType::TIME_FIELD){
                    if($fieldKind === $checkinAtFieldKind){
                        $maxParamsValueNumber = 1;
                    }else if($fieldKind === $checkoutAtFieldKind){
                        $maxParamsValueNumber = 1;
                    }else if($fieldKind === $breakTimeStartAtFieldKind){
                        if($timeFieldParamsBreakTimeStartAts){
                            $maxParamsValueNumber = count($timeFieldParamsBreakTimeStartAts);
                        }//$timeFieldParamsBreakTimeStartAts
                    }else if($fieldKind === $breakTimeEndAtFieldKind){
                        if($timeFieldParamsBreakTimeEndAts){
                            $maxParamsValueNumber = count($timeFieldParamsBreakTimeEndAts);
                        }//$timeFieldParamsBreakTimeEndAts
                    }else if($fieldKind === $descriptionFieldKind){
                        $maxParamsValueNumber = 1;
                    }//$fieldKind
                }else if($timeFieldType === TimeFieldType::TIME_FIELD_PAIR){
                    if($fieldKind === $checkinCheckoutFieldKind){
                        $maxParamsValueNumber = 1;
                    }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                        if($timeFieldPairParamsBreakTimeStartBreakTimeEnds){
                            $maxParamsValueNumber = count($timeFieldPairParamsBreakTimeStartBreakTimeEnds);
                        }//$timeFieldPairParamsBreakTimeStartBreakTimeEnds
                    }//$fieldKind
                }//$timeFieldType

                for($paramsValueNumber=1;$paramsValueNumber<=$maxParamsValueNumber;$paramsValueNumber++){
                    $timeFieldParamsValue = null;
                    $timeFieldPairParamsValue = null;
                    if($timeFieldType === TimeFieldType::TIME_FIELD){
                        if($fieldKind === $checkinAtFieldKind){
                            $timeFieldParamsValue = $timeFieldParamsCheckinAt;
                        }else if($fieldKind === $checkoutAtFieldKind){
                            $timeFieldParamsValue = $timeFieldParamsCheckoutAt;
                        }else if($fieldKind === $breakTimeStartAtFieldKind){
                            $timeFieldParamsValue = $timeFieldParamsBreakTimeStartAts[$paramsValueNumber - 1];
                        }else if($fieldKind === $breakTimeEndAtFieldKind){
                            $timeFieldParamsValue = $timeFieldParamsBreakTimeEndAts[$paramsValueNumber - 1];
                        }else if($fieldKind === $descriptionFieldKind){
                            $timeFieldParamsValue = $timeFieldParamsDescription;
                        }//$fieldKind
                    }else if($timeFieldType === TimeFieldType::TIME_FIELD_PAIR){
                        if($fieldKind === $checkinCheckoutFieldKind){
                            $timeFieldPairParamsValue = $timeFieldPairParamsCheckinCheckout;
                        }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                            $timeFieldPairParamsValue = $timeFieldPairParamsBreakTimeStartBreakTimeEnds[$paramsValueNumber - 1];
                        }//$fieldKind
                    }//$timeFieldType

                    $stringFieldName = null;
                    $undefinedErrorStatus = 0;
                    $errorStatus = 0;
                    $errorMessage = null;
                    if($timeFieldType === TimeFieldType::TIME_FIELD){
                        if($timeFieldParamsValue){
                            $stringFieldName = $timeFieldParamsValue->string_field_name;
                            $timeFieldErrorStatus = $timeFieldParamsValue->time_field_error_status;
                            if($timeFieldErrorStatus === TimeFieldErrorStatus::TIME_PARSE){
                                $errorStatus = $timeFieldParamsValue->time_parse_error_status;
                                $errorMessage = TimeParseErrorStatus::message($errorStatus);
                                $undefinedErrorStatus = TimeParseErrorStatus::UNDEFINED;
                            }else{//$timeFieldErrorStatus
                                $errorStatus = $timeFieldErrorStatus;
                                $errorMessage = TimeFieldErrorStatus::message($errorStatus);
                                $undefinedErrorStatus = TimeFieldErrorStatus::UNDEFINED;
                            }//$timeFieldErrorStatus
                        }//$timeFieldParamsValue
                    }else if($timeFieldType === TimeFieldType::TIME_FIELD_PAIR){
                        if($timeFieldPairParamsValue){
                            $stringFieldName = $timeFieldPairParamsValue->string_field_name;
                            $errorStatus = $timeFieldPairParamsValue->time_field_pair_error_status;
                            $errorMessage = TimeFieldPairErrorStatus::message($errorStatus);
                            $undefinedErrorStatus = TimeFieldPairErrorStatus::UNDEFINED;
                        }//$timeFieldPairParamsValue
                    }//$timeFieldType

                    if($errorStatus !== $undefinedErrorStatus){
                        if($stringFieldName){
                            $validator->errors()->add($stringFieldName,$errorMessage);
                        }//$stringFieldName
                    }//$timeFieldParamsCheckinAt

                }//stringValueNumber
            }//$fieldKind
        }//$timeFieldType

    }//setDraftTimetableListValidation

    private static function getDraftTimetablesProperties(
        $timeFieldPairParamsCheckinCheckout,
        $timeFieldPairParamsBreakTimeStartBreakTimeEnds,
        $timeFieldParamsDescription
    ){
        $checkinCheckoutFieldKind = TimeFieldPairKind::CHECKIN_CHECKOUT;
        $breakTimeStartBreakTimeEndFieldKind = TimeFieldPairKind::BREAK_TIME_START_BREAK_TIME_END;
        
        $draftTimetableDTO = null;
        $draftBreakTimeDTOs = null; 

        $stringFieldNames = TimeFieldPairKind::fieldNames();
        $maxFieldKind = 0;
        if($stringFieldNames){
            $maxFieldKind = count($stringFieldNames);
        }//$stringFieldNames

        for($fieldKind=1;$fieldKind<=$maxFieldKind;$fieldKind++){
            $maxParamsValueNumber = 0;
            $fieldType = FieldType::UNDEFINED;
            if($fieldKind === $checkinCheckoutFieldKind){
                $maxParamsValueNumber = 1;
                $fieldType = FieldType::VALUE;
            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                if($timeFieldPairParamsBreakTimeStartBreakTimeEnds){
                    $maxParamsValueNumber = count($timeFieldPairParamsBreakTimeStartBreakTimeEnds);
                }//$timeFieldPairParamsBreakTimeStartBreakTimeEnds
                $fieldType = FieldType::ARRAY;
            }//$fieldKind

            $maxLoopTime = 0;
            if($maxParamsValueNumber >= 1){
                if($fieldType === FieldType::VALUE){
                    $maxLoopTime = 1;
                }else if($fieldType === FieldType::ARRAY){
                    $maxLoopTime = 2;
                }//$fieldKind
            }//$maxParamsValueNumber&1

            for($loopTime=1;$loopTime<=$maxLoopTime;$loopTime++){
                $loopTimeEndMarker = 0;
                if($loopTime == $maxLoopTime){
                    $loopTimeEndMarker = 1;
                }

                $validParamsValueNumber = 0;
                for($paramsValueNumber = 1; $paramsValueNumber <= $maxParamsValueNumber; $paramsValueNumber++){
                    if($fieldKind === $checkinCheckoutFieldKind){
                        $paramsValue = $timeFieldPairParamsCheckinCheckout;
                    }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                        $paramsValue = $timeFieldPairParamsBreakTimeStartBreakTimeEnds[$paramsValueNumber - 1];
                    }//$fieldKind

                    $startTimeAt = $paramsValue->start_time_at;
                    $endTimeAt = $paramsValue->end_time_at;
                    $panStartTimeAt = $paramsValue->pan_start_time_at;
                    $panEndTimeAt = $paramsValue->pan_end_time_at;
                    $stringFieldName = $paramsValue->string_field_name;
                    $timeFieldPairErrorStatus = $paramsValue->time_field_pair_error_status;

                    $validParamsValueNumberAdditionMarker = 0;
                    if($panStartTimeAt&&$panEndTimeAt){
                        if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            $validParamsValueNumberAdditionMarker = 1;
                        }//$timeFieldPairErrorStatus
                    }//$panStartTimeAt&$panEndTimeAt

                    if($validParamsValueNumberAdditionMarker !== 0){
                        $validParamsValueNumber = $validParamsValueNumber + 1;

                        if($loopTimeEndMarker !== 0){
                            if($fieldKind === $checkinCheckoutFieldKind){
                                $draftTimetableDTO = new DraftTimetableDTO();
                                $draftTimetableDTO->checkin_at = $startTimeAt;
                                $draftTimetableDTO->checkout_at = $endTimeAt;
                                $draftTimetableDTO->pan_checkin_at = $panStartTimeAt;
                                $draftTimetableDTO->pan_checkout_at = $panEndTimeAt;
                                if($timeFieldParamsDescription){
                                    $draftTimetableDTO->description = $timeFieldParamsDescription -> string_value;
                                }else{//$timeFieldParamsDescription
                                    $draftTimetableDTO->description = null;
                                }//$timeFieldParamsDescription
                            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                                $draftBreakTimeDTO = new DraftBreakTimeDTO();
                                $draftBreakTimeDTO->break_time_start_at = $startTimeAt;
                                $draftBreakTimeDTO->break_time_end_at = $endTimeAt;
                                $draftBreakTimeDTO->pan_break_time_start_at = $panStartTimeAt;
                                $draftBreakTimeDTO->pan_break_time_end_at = $panEndTimeAt;
                                $draftBreakTimeDTOs[$validParamsValueNumber - 1] = $draftBreakTimeDTO;
                            }//$fieldKind
                        }//$loopTimeEndMarker&0
                    }//$validParamsValueNumberAdditionMarker&0
                }//$paramsValueNumber

                if($loopTime === 1){
                    if($fieldKind === $checkinCheckoutFieldKind){
                    }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                        if($validParamsValueNumber >= 1){
                            $draftBreakTimeDTOs = array_fill(0, $validParamsValueNumber, null);
                        }//$validParamsValueNumber&1
                    }//$fieldKind
                }//$loopTime
            }//$loopTime
        }//$fieldKind

        $draftTimetablesProperties = [
            "draftTimetableDTO" => $draftTimetableDTO,
            "draftBreakTimeDTOs" => $draftBreakTimeDTOs,
        ];

        return($draftTimetablesProperties);

    }//getDraftTimetableLists

    private static function getRearrangedDraftBreakTimeDTOs(
        $draftBreakTimeDTOs
    ){
        $maxBreakTimeNumber = 0;
        if($draftBreakTimeDTOs){
            $maxBreakTimeNumber = count($draftBreakTimeDTOs);
        }//$draftBreakTimeDTOs

        if($maxBreakTimeNumber >= 2){
            for($firstBreakTimeNumber = 1;$firstBreakTimeNumber <= $maxBreakTimeNumber - 1; $firstBreakTimeNumber++){
                for($secondBreakTimeNumber = $firstBreakTimeNumber + 1;$secondBreakTimeNumber <= $maxBreakTimeNumber;$secondBreakTimeNumber++){
                    $firstDraftBreakTimeDTO = $draftBreakTimeDTOs[$firstBreakTimeNumber - 1];
                    $firstPanBreakTimeStartAt = $firstDraftBreakTimeDTO->pan_break_time_start_at;
                    $secondDraftBreakTimeDTO = $draftBreakTimeDTOs[$secondBreakTimeNumber - 1];
                    $secondPanBreakTimeStartAt = $secondDraftBreakTimeDTO->pan_break_time_start_at;

                    if($firstPanBreakTimeStartAt&&$secondPanBreakTimeStartAt){
                        if($firstPanBreakTimeStartAt ->lt($secondPanBreakTimeStartAt)){
                            $hasSwap = false;
                        }else{
                            $hasSwap = true;
                        }
                    }else{
                        $hasSwap = false;
                    }//$firstPanBreakTimeStartAt&&$secondPanBreakTimeStartAt

                    $draftBreakTimeDTOs[$firstBreakTimeNumber - 1] = null;
                    $draftBreakTimeDTOs[$secondBreakTimeNumber - 1] = null;
                    if($hasSwap === false){
                        $draftBreakTimeDTOs[$firstBreakTimeNumber - 1] = $firstDraftBreakTimeDTO;
                        $draftBreakTimeDTOs[$secondBreakTimeNumber - 1] = $secondDraftBreakTimeDTO;
                    }else{//$hasSwap
                        $draftBreakTimeDTOs[$firstBreakTimeNumber - 1] = $secondDraftBreakTimeDTO;
                        $draftBreakTimeDTOs[$secondBreakTimeNumber - 1] = $firstDraftBreakTimeDTO;
                    }//$hasSwap

                }//$firstBreakTimeNumber
            }//$breakTimeNumber
        }//$maxBreakTimeNumber

        return($draftBreakTimeDTOs);

    }//getRearrangedDraftBreakTimeDTOs

    public static function checkDraftTimetableListValidator(
        $validator,
        $carbonNow,
        $stringValueFields,
        $referencedAt
    ){
        $timeFieldParamsValues = TimeFieldParamsService::getTimeFieldParamsValues($carbonNow,$stringValueFields,$referencedAt);
        $timeFieldParamsCheckinAt = $timeFieldParamsValues["timeFieldParamsCheckinAt"];
        $timeFieldParamsCheckoutAt = $timeFieldParamsValues["timeFieldParamsCheckoutAt"];
        $timeFieldParamsBreakTimeStartAts = $timeFieldParamsValues["timeFieldParamsBreakTimeStartAts"];
        $timeFieldParamsBreakTimeEndAts = $timeFieldParamsValues["timeFieldParamsBreakTimeEndAts"];
        $timeFieldParamsDescription = $timeFieldParamsValues["timeFieldParamsDescription"];

        $timeFieldPairValues = TimeFieldParamsService::getTimeFieldPairValues(
            $carbonNow,
            $timeFieldParamsCheckinAt,
            $timeFieldParamsCheckoutAt,
            $timeFieldParamsBreakTimeStartAts,
            $timeFieldParamsBreakTimeEndAts,
            $timeFieldParamsDescription
        );

        $timeFieldPairParamsCheckinCheckout = $timeFieldPairValues["timeFieldPairParamsCheckinCheckout"];
        $timeFieldPairParamsBreakTimeStartBreakTimeEnds = $timeFieldPairValues["timeFieldPairParamsBreakTimeStartBreakTimeEnds"];

        TimeFieldParamsService::checkBreakTimesOverlap($timeFieldPairParamsBreakTimeStartBreakTimeEnds);

        self::setDraftTimetableListValidation(
            $validator,
            $timeFieldParamsCheckinAt,
            $timeFieldParamsCheckoutAt,
            $timeFieldParamsBreakTimeStartAts,
            $timeFieldParamsBreakTimeEndAts,
            $timeFieldParamsDescription,
            $timeFieldPairParamsCheckinCheckout,
            $timeFieldPairParamsBreakTimeStartBreakTimeEnds
        );

        $draftTimetablesProperties = self::getDraftTimetablesProperties(
            $timeFieldPairParamsCheckinCheckout,
            $timeFieldPairParamsBreakTimeStartBreakTimeEnds,
            $timeFieldParamsDescription
        );

        $draftTimetableDTO = $draftTimetablesProperties["draftTimetableDTO"];
        $draftBreakTimeDTOs = $draftTimetablesProperties["draftBreakTimeDTOs"];

        $draftBreakTimeDTOs = self::getRearrangedDraftBreakTimeDTOs(
            $draftBreakTimeDTOs
        );


        $validatorResults = [
            "draftTimetableDTO" => $draftTimetableDTO,
            "draftBreakTimeDTOs" => $draftBreakTimeDTOs,
        ];

        return $validatorResults;

    }//function checkDraftTimetableListValidator


}//DraftTimetableListService