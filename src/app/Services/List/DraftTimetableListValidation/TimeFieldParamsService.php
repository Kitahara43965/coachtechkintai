<?php

namespace App\Services\List\DraftTimetableListValidation;
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

class TimeFieldParamsService{
    public static function getTimeFieldParamsValues($carbonNow,$stringValueFields,$referencedAt = null){
        $checkinAtFieldKind = TimeFieldKind::CHECKIN_AT;
        $checkoutAtFieldKind = TimeFieldKind::CHECKOUT_AT;
        $breakTimeStartAtFieldKind = TimeFieldKind::BREAK_TIME_START_AT;
        $breakTimeEndAtFieldKind = TimeFieldKind::BREAK_TIME_END_AT;
        $descriptionFieldKind = TimeFieldKind::DESCRIPTION;

        $stringFieldNames = TimeFieldKind::fieldNames();
        $maxFieldKind = 0;
        if($stringFieldNames){
            $maxFieldKind = count($stringFieldNames);
        }//$stringFieldNames


        $timeFieldParamsCheckinAt = null;
        $timeFieldParamsCheckoutAt = null;
        $timeFieldParamsBreakTimeStartAts = null;
        $timeFieldParamsBreakTimeEndAts = null;
        $timeFieldParamsDescription = null;
        $checkinAt = null;
        $checkoutAt = null;

        for($fieldKind=1;$fieldKind<=$maxFieldKind;$fieldKind++){
            $stringFieldName = $stringFieldNames[$fieldKind - 1];

            $fieldType = FieldType::UNDEFINED;
            $hasValueCheck = false;
            $hasNoErrorCheck = false;
            $isTimeValue = false;
            if($fieldKind === $checkinAtFieldKind){
                $fieldType = FieldType::VALUE;
                $hasValueCheck = true;
                $hasNoErrorCheck = false;
                $isTimeValue = true;
            }else if($fieldKind === $checkoutAtFieldKind){
                $fieldType = FieldType::VALUE;
                $hasValueCheck = true;
                $hasNoErrorCheck = false;
                $isTimeValue = true;
            }else if($fieldKind === $breakTimeStartAtFieldKind){
                $fieldType = FieldType::ARRAY;
                $hasValueCheck = true;
                $hasNoErrorCheck = false;
                $isTimeValue = true;
            }else if($fieldKind === $breakTimeEndAtFieldKind){
                $fieldType = FieldType::ARRAY;
                $hasValueCheck = true;
                $hasNoErrorCheck = false;
                $isTimeValue = true;
            }else if($fieldKind === $descriptionFieldKind){
                $fieldType = FieldType::VALUE;
                $hasValueCheck = true;
                $hasNoErrorCheck = true;
                $isTimeValue = false;
            }//$fieldKind

            $stringValues = null;
            $maxStringValueNumber = 0;
            $timeFieldParamsValues = null;
            if($stringFieldName){
                if($fieldType === FieldType::VALUE){
                    $stringValues = array_fill(0, 1, null);
                    $stringValues[1 - 1] = $stringValueFields[$stringFieldName] ?? null;
                    $maxStringValueNumber = 1;
                }else if($fieldType === FieldType::ARRAY){
                    $stringValues = $stringValueFields[$stringFieldName] ?? [];
                    if($stringValues){
                        $maxStringValueNumber = count($stringValues);
                    }//$stringValues
                }//$fieldType
                if($maxStringValueNumber >= 1){
                    $timeFieldParamsValues = array_fill(0, $maxStringValueNumber, null);
                }//$maxStringValueNumber&1
            }//$stringFieldName

            for($stringValueNumber=1;$stringValueNumber<=$maxStringValueNumber;$stringValueNumber++){

                $stringValue = null;
                $stringSuffixedFieldName = null;
                if($fieldType === FieldType::VALUE){
                    $stringValue = $stringValues[1 - 1];
                    $stringSuffixedFieldName = $stringFieldName;
                }else if($fieldType === FieldType::ARRAY){
                    $stringValue = $stringValues[$stringValueNumber - 1];
                    $stringSuffixedFieldName = $stringFieldName.'.'.($stringValueNumber - 1);
                }//$fieldType

                $timeFieldParamsValue = new TimeFieldParams();

                $timeParseParamsValue = TimeParseService::resolveStringValueToTimeParseParams(
                    $stringSuffixedFieldName,
                    $stringValue,
                    $referencedAt,
                    $hasNoErrorCheck
                );

                $timeParseErrorStatus = $timeParseParamsValue->time_parse_error_status;
                $carbonTime = $timeParseParamsValue->carbon_time;

                $timeFieldErrorStatus = TimeFieldErrorStatus::UNDEFINED;
                if($timeParseErrorStatus !== TimeParseErrorStatus::UNDEFINED){
                    $timeFieldErrorStatus = TimeFieldErrorStatus::TIME_PARSE;
                }//timeParseErrorStatus

                $maxStringValueCharNumber = 0;
                if($stringValue){
                    $maxStringValueCharNumber = mb_strlen($stringValue);
                }//$stringValue

                if($stringValue === null || $stringValue === ''){
                    $hasValue = false;
                }else{
                    $hasValue = true;
                }

                if($hasValueCheck === true){
                    if($hasValue === false){
                        if($timeFieldErrorStatus === TimeFieldErrorStatus::UNDEFINED){
                            if($fieldKind === $checkinAtFieldKind){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::TIME_VALUE_NO_VALUE;
                            }else if($fieldKind === $checkoutAtFieldKind){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::TIME_VALUE_NO_VALUE;
                            }else if($fieldKind === $breakTimeStartAtFieldKind){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::TIME_VALUE_NO_VALUE;
                            }else if($fieldKind === $breakTimeEndAtFieldKind){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::TIME_VALUE_NO_VALUE;
                            }else if($fieldKind === $descriptionFieldKind){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::DESCRIPTION_NO_VALUE;
                            }//$fieldKind
                        }//$timeFieldErrorStatus
                    }//$hasValue
                }//$hasValueCheck&true

                if($maxStringValueCharNumber > TimeFieldErrorStatus::MAX_STRING_VALUE_CHAR_NUMBER){
                    if($timeFieldErrorStatus === TimeFieldErrorStatus::UNDEFINED){
                        $timeFieldErrorStatus = TimeFieldErrorStatus::TOO_LONG_STRING_VALUE;
                    }//$timeFieldErrorStatus
                }//$maxStringValueCharNumber

                if($isTimeValue === true){
                    if($carbonTime&&$carbonNow){
                        if($carbonTime -> gt($carbonNow)){
                            if($timeFieldErrorStatus === TimeFieldErrorStatus::UNDEFINED){
                                $timeFieldErrorStatus = TimeFieldErrorStatus::FUTURE_TIME;
                            }//$timeFieldErrorStatus
                        }
                    }//$carbonTime$carbonNow
                }//$isTimeValue

                $timeFieldParamsValue->carbon_time = $carbonTime;
                $timeFieldParamsValue->has_value = $hasValue;
                $timeFieldParamsValue->string_value = $stringValue;
                $timeFieldParamsValue->string_field_name = $stringSuffixedFieldName;
                $timeFieldParamsValue->time_parse_error_status = $timeParseErrorStatus;
                $timeFieldParamsValue->time_field_error_status = $timeFieldErrorStatus;

                $timeFieldParamsValues[$stringValueNumber - 1] = $timeFieldParamsValue;

            }//$stringValueNumber

            if($fieldKind === $checkinAtFieldKind){
                $timeFieldParamsCheckinAt = $timeFieldParamsValues[1 - 1];
            }else if($fieldKind === $checkoutAtFieldKind){
                $timeFieldParamsCheckoutAt = $timeFieldParamsValues[1 - 1];
            }else if($fieldKind === $breakTimeStartAtFieldKind){
                $timeFieldParamsBreakTimeStartAts = $timeFieldParamsValues;
            }else if($fieldKind === $breakTimeEndAtFieldKind){
                $timeFieldParamsBreakTimeEndAts = $timeFieldParamsValues;
            }else if($fieldKind === $descriptionFieldKind){
                $timeFieldParamsDescription = $timeFieldParamsValues[1 - 1];
            }//$fieldKind

        }//$fieldKind

        $results = [
            "timeFieldParamsCheckinAt" => $timeFieldParamsCheckinAt,
            "timeFieldParamsCheckoutAt" => $timeFieldParamsCheckoutAt,
            "timeFieldParamsBreakTimeStartAts" => $timeFieldParamsBreakTimeStartAts,
            "timeFieldParamsBreakTimeEndAts" => $timeFieldParamsBreakTimeEndAts,
            "timeFieldParamsDescription" => $timeFieldParamsDescription,
        ];

        return($results);

    }//getTimeFieldParamsValues


    public static function getTimeFieldPairValues(
        $carbonNow,
        $timeFieldParamsCheckinAt,
        $timeFieldParamsCheckoutAt,
        $timeFieldParamsBreakTimeStartAts,
        $timeFieldParamsBreakTimeEndAts,
        $timeFieldParamsDescription
    ){
        $checkinCheckoutFieldKind = TimeFieldPairKind::CHECKIN_CHECKOUT;
        $breakTimeStartBreakTimeEndFieldKind = TimeFieldPairKind::BREAK_TIME_START_BREAK_TIME_END;
        
        $stringFieldNames = TimeFieldPairKind::fieldNames();
        $maxFieldKind = 0;
        if($stringFieldNames){
            $maxFieldKind = count($stringFieldNames);
        }//$stringFieldNames

        $timeFieldPairParamsCheckinCheckout = null;
        $timeFieldPairParamsBreakTimeStartBreakTimeEnds = null;

        $isPanCheckinPanCheckoutValid = false;
        $panCheckinAt = null;
        $panCheckoutAt = null;

        for($fieldKind=1;$fieldKind<=$maxFieldKind;$fieldKind++){
            $stringFieldName = $stringFieldNames[$fieldKind - 1];

            $fieldType = FieldType::UNDEFINED;
            $timeFieldParamsStartTimeAts = null;
            $timeFieldParamsEndTimeAts = null;
            if($fieldKind === $checkinCheckoutFieldKind){
                $fieldType = FieldType::VALUE;
                $timeFieldParamsStartTimeAts = array_fill(0, 1, null);
                $timeFieldParamsEndTimeAts = array_fill(0, 1, null);
                $timeFieldParamsStartTimeAts[1 - 1] = $timeFieldParamsCheckinAt;
                $timeFieldParamsEndTimeAts[1 - 1] = $timeFieldParamsCheckoutAt;
            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                $fieldType = FieldType::ARRAY;
                $timeFieldParamsStartTimeAts = $timeFieldParamsBreakTimeStartAts;
                $timeFieldParamsEndTimeAts = $timeFieldParamsBreakTimeEndAts;
            }//$fieldKind

            $maxStartTimeAtNumber = 0;
            if($timeFieldParamsStartTimeAts){
                $maxStartTimeAtNumber = count($timeFieldParamsStartTimeAts);
            }//$timeFieldParamsStartTimeAts

            $maxEndTimeAtNumber = 0;
            if($timeFieldParamsEndTimeAts){
                $maxEndTimeAtNumber = count($timeFieldParamsEndTimeAts);
            }//$timeFieldParamsEndTimeAts

            
            $maxParamsValueNumber = 0;
            $timeFieldPairParamsValues = null;
            if($stringFieldName){
                if($maxStartTimeAtNumber <= $maxEndTimeAtNumber){
                    $maxParamsValueNumber = $maxEndTimeAtNumber;
                }else{//$maxStartTimeAtNumber
                    $maxParamsValueNumber = $maxStartTimeAtNumber;
                }//$maxStartTimeAtNumber
                if($maxParamsValueNumber >= 1){
                    $timeFieldPairParamsValues = array_fill(0, $maxParamsValueNumber, null);
                }//$maxParamsValueNumber&1
            }//$stringFieldName

            for($paramsValueNumber=1;$paramsValueNumber<=$maxParamsValueNumber;$paramsValueNumber++){

                $timeFieldPairParamsValue = new TimeFieldPairParams();;

                $stringSuffixedFieldName = null;
                if($fieldType === FieldType::VALUE){
                    $stringSuffixedFieldName = $stringFieldName;
                }else if($fieldType === FieldType::ARRAY){
                    $stringSuffixedFieldName = $stringFieldName.'.'.($paramsValueNumber - 1);
                }//$fieldType

                $timeFieldParamsStartTimeAt = null;
                if($paramsValueNumber <= $maxStartTimeAtNumber){
                    $timeFieldParamsStartTimeAt = $timeFieldParamsStartTimeAts[$paramsValueNumber - 1];
                }//$paramsValueNumber

                $timeFieldParamsEndTimeAt = null;
                if($paramsValueNumber <= $maxEndTimeAtNumber){
                    $timeFieldParamsEndTimeAt = $timeFieldParamsEndTimeAts[$paramsValueNumber - 1];
                }//$paramsValueNumber

                $startTimeAtTimeFieldErrorStatus = TimeFieldErrorStatus::UNDEFINED;
                $startTimeAtHasValue = false;
                $startTimeAtStringFieldName = null;
                $startTimeAt = null;
                if($timeFieldParamsStartTimeAt){
                    $startTimeAtTimeFieldErrorStatus = $timeFieldParamsStartTimeAt->time_field_error_status;
                    $startTimeAtHasValue = $timeFieldParamsStartTimeAt->has_value;
                    $startTimeAtStringFieldName = $timeFieldParamsStartTimeAt->string_field_name;
                    $startTimeAt = $timeFieldParamsStartTimeAt->carbon_time;
                }//$timeFieldParamsStartTimeAt

                $endTimeAtTimeFieldErrorStatus = TimeFieldErrorStatus::UNDEFINED;
                $endTimeAtHasValue = false;
                $endTimeAtStringFieldName = null;
                $endTimeAt = null;
                if($timeFieldParamsEndTimeAt){
                    $endTimeAtTimeFieldErrorStatus = $timeFieldParamsEndTimeAt->time_field_error_status;
                    $endTimeAtHasValue = $timeFieldParamsEndTimeAt->has_value;
                    $endTimeAtStringFieldName = $timeFieldParamsEndTimeAt->string_field_name;
                    $endTimeAt = $timeFieldParamsEndTimeAt->carbon_time;
                }//$timeFieldParamsEndTimeAt

                $panStartTimeAt = null;
                $panEndTimeAt = null;
                if($startTimeAt){
                    $panStartTimeAt = $startTimeAt;
                    if($endTimeAt){
                        $panEndTimeAt = $endTimeAt;
                    }else{//$endTimeAt
                        if($endTimeAtHasValue === false){
                            $panEndTimeAt = $carbonNow;
                        }//$endTimeAtHasValue
                    }//$endTimeAt
                }//$startTimeAt

                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::UNDEFINED;
                if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                    if($startTimeAtTimeFieldErrorStatus !== TimeFieldErrorStatus::UNDEFINED){
                        $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::ELEMENT_WRONG;
                    }//$startTimeAtTimeFieldErrorStatus
                    if($endTimeAtTimeFieldErrorStatus !== TimeFieldErrorStatus::UNDEFINED){
                        $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::ELEMENT_WRONG;
                    }//$endTimeAtTimeFieldErrorStatus
                }//$timeFieldPairErrorStatus

                if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                    if($panStartTimeAt && $panCheckinAt){
                        if($panStartTimeAt -> lt($panCheckinAt)){
                            if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_START_DURATION_OVERFLOW;
                            }//$timeFieldPairErrorStatus
                        }//$panStartTimeAt&&$panCheckinAt
                        if($panStartTimeAt -> gt($panCheckoutAt)){
                            if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_START_DURATION_OVERFLOW;
                            }//$timeFieldPairErrorStatus
                        }//$panStartTimeAt&&$panCheckinAt
                    }//$panStartTimeAt&&$panCheckinAt

                    if($panEndTimeAt && $panCheckoutAt){
                        if($panEndTimeAt -> gt($panCheckoutAt)){
                            if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_END_DURATION_OVERFLOW;
                            }//$timeFieldPairErrorStatus
                        }//$panEndTimeAt&&$panCheckoutAt
                    }//$panEndTimeAt&&$panCheckoutAt
                }//$fieldKind

                if($panStartTimeAt && $panEndTimeAt){
                    if($panStartTimeAt ->eq($panEndTimeAt)){
                        if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            if($fieldKind === $checkinCheckoutFieldKind){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::CHECKIN_CHECKOUT_START_END_SAME;
                            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_START_BREAK_TIME_END_START_END_SAME;
                            }//$fieldKind
                        }//$timeFieldPairErrorStatus
                    }else if($panStartTimeAt -> gt($panEndTimeAt)){
                        if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            if($fieldKind === $checkinCheckoutFieldKind){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::CHECKIN_CHECKOUT_REVERSE_ENDS;
                            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_START_BREAK_TIME_END_REVERSE_ENDS;
                            }//$fieldKind
                        }//$timeFieldPairErrorStatus
                    }
                }//$startTimeAt&&$panEndTimeAt

                if($startTimeAtHasValue === false){
                    if($endTimeAtHasValue === true){
                        if($timeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                                $timeFieldPairErrorStatus = TimeFieldPairErrorStatus::EMPTY_BREAK_TIME_START_FILLED_BREAK_TIME_END;
                            }//$fieldKind
                        }//$timeFieldPairErrorStatus
                    }//$endTimeAtHasValue
                }//$startTimeAtHasValue


                if($fieldKind === $checkinCheckoutFieldKind){
                    $checkinAt = $startTimeAt;
                    $checkoutAt = $endTimeAt;
                    $panCheckinAt = $panStartTimeAt;
                    $panCheckoutAt = $panEndTimeAt;
                }//$fieldKind

                $timeFieldPairParamsValue->start_time_at_time_field_error_status = $startTimeAtTimeFieldErrorStatus;
                $timeFieldPairParamsValue->end_time_at_time_field_error_status = $endTimeAtTimeFieldErrorStatus;
                $timeFieldPairParamsValue->string_field_name = $stringSuffixedFieldName;
                $timeFieldPairParamsValue->time_field_pair_error_status = $timeFieldPairErrorStatus;
                $timeFieldPairParamsValue->start_time_at = $startTimeAt;
                $timeFieldPairParamsValue->end_time_at = $endTimeAt;
                $timeFieldPairParamsValue->pan_start_time_at = $panStartTimeAt;
                $timeFieldPairParamsValue->pan_end_time_at = $panEndTimeAt;

                $timeFieldPairParamsValues[$paramsValueNumber - 1] = $timeFieldPairParamsValue;
            }//$paramsValueNumber

            if($fieldKind === $checkinCheckoutFieldKind){
                $timeFieldPairParamsCheckinCheckout = $timeFieldPairParamsValues[1 - 1];
            }else if($fieldKind === $breakTimeStartBreakTimeEndFieldKind){
                $timeFieldPairParamsBreakTimeStartBreakTimeEnds = $timeFieldPairParamsValues;
            }//$fieldKind

        }//$fieldKind

        $timeFieldPairValues = [
            "timeFieldPairParamsCheckinCheckout" => $timeFieldPairParamsCheckinCheckout,
            "timeFieldPairParamsBreakTimeStartBreakTimeEnds" => $timeFieldPairParamsBreakTimeStartBreakTimeEnds,
        ];

        return($timeFieldPairValues);

    }//getTimeFieldPairValues

    public static function checkBreakTimesOverlap($timeFieldPairParamsBreakTimeStartBreakTimeEnds):void{
        $maxParamsValueNumber = 0;
        if($timeFieldPairParamsBreakTimeStartBreakTimeEnds){
            $maxParamsValueNumber = count($timeFieldPairParamsBreakTimeStartBreakTimeEnds);
        }//$timeFieldPairParamsBreakTimeStartBreakTimeEnds

        if($maxParamsValueNumber >= 2){
            for($firstParamsValueNumber=1;$firstParamsValueNumber<=$maxParamsValueNumber-1;$firstParamsValueNumber++){
                $firstParamsValue = $timeFieldPairParamsBreakTimeStartBreakTimeEnds[$firstParamsValueNumber - 1];
                $firstPanStartTimeAt = $firstParamsValue->pan_start_time_at;
                $firstPanEndTimeAt = $firstParamsValue->pan_end_time_at;
                $firstStringFieldName = $firstParamsValue->string_field_name;
                $firstTimeFieldPairErrorStatus = $firstParamsValue->time_field_pair_error_status;
                $firstIsComparablePanStartTimePanEndTime = false;
                if($firstPanStartTimeAt && $firstPanEndTimeAt){
                    if($firstTimeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                        $firstIsComparablePanStartTimePanEndTime = true;
                    }//$firstTimeFieldPairErrorStatus
                }

                for($secondParamsValueNumber=$firstParamsValueNumber+1;$secondParamsValueNumber<=$maxParamsValueNumber;$secondParamsValueNumber++){
                    $secondParamsValue = $timeFieldPairParamsBreakTimeStartBreakTimeEnds[$secondParamsValueNumber - 1];
                    $secondPanStartTimeAt = $secondParamsValue->pan_start_time_at;
                    $secondPanEndTimeAt = $secondParamsValue->pan_end_time_at;
                    $secondStringFieldName = $secondParamsValue->string_field_name;
                    $secondTimeFieldPairErrorStatus = $secondParamsValue->time_field_pair_error_status;

                    $secondIsComparablePanStartTimePanEndTime = false;
                    if($secondPanStartTimeAt && $secondPanEndTimeAt){
                        if($secondTimeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            $secondIsComparablePanStartTimePanEndTime = true;
                        }//$firstTimeFieldPairErrorStatus
                    }

                    $isNotComparable = false;
                    if($firstIsComparablePanStartTimePanEndTime === false){
                        $isNotComparable = true;
                    }//$firstIsComparablePanStartTimePanEndTime
                    if($secondIsComparablePanStartTimePanEndTime === false){
                        $isNotComparable = true;
                    }//$secondIsComparablePanStartTimePanEndTime

                    $isOverlapped = false;
                    if($isNotComparable === false){
                        $isOverlapped = OverlapService::getIsOverlapped(
                            $firstPanStartTimeAt,
                            $firstPanEndTimeAt,
                            $secondPanStartTimeAt,
                            $secondPanEndTimeAt
                        );
                    }//$isNotComparable

                    if($isOverlapped === true){
                        if($secondTimeFieldPairErrorStatus === TimeFieldPairErrorStatus::UNDEFINED){
                            $secondTimeFieldPairErrorStatus = TimeFieldPairErrorStatus::BREAK_TIME_OVERLAP;
                        }//$secondTimeFieldPairErrorStatus
                    }//$isOverlapped

                    $secondParamsValue->time_field_pair_error_status = $secondTimeFieldPairErrorStatus;

                }//$secondParamsValueNumber

                $firstParamsValue->time_field_pair_error_status = $firstTimeFieldPairErrorStatus;

            }//$firstParamsValueNumber
        }//$maxParamsValueNumber&1

    }//checkTimeFieldPairParamsParamsValuesDurationOverlap
}