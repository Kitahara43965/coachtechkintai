<?php

namespace App\Services\Time;
use App\Constants\TimeParseErrorStatus;
use App\DTOs\TimeParseParams;
use Carbon\Carbon;


class TimeParseService{

    public const IS_SECOND_ALLOWED = false;

    public static function resolveStringValueToTimeParseParams(
        $stringFieldName = null,$stringValue = null,
        $referencedAt = null,$hasNoErrorCheck=false
    ){

        $carbonNow = Carbon::now();
        if($referencedAt){
            $initialYear = $referencedAt->year;
            $initialMonth = $referencedAt->month;
            $initialDay = $referencedAt->day;
        }else{//$referencedAt
            $initialYear = $carbonNow->year;
            $initialMonth = $carbonNow->month;
            $initialDay = $carbonNow->day;
        }//$referencedAt

        $timeParseParamsValue = new TimeParseParams();
        $timeParseParamsValue->string_field_name = $stringFieldName;
        $timeParseParamsValue->string_value = $stringValue;


        $candidateIsTime = false;
        $timeParseErrorStatus = TimeParseErrorStatus::UNDEFINED;

        $year = 0;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;

        if($stringValue === null){
        }else if($stringValue === ''){
        }else{//$stringValue
            $normalized = $stringValue;

            // 記号・空白の正規化（最優先）
            $normalized = str_replace(
                ["\xEF\xBC\x9A", "\xEF\xBC\x8F", "\xE3\x80\x80", "\xC2\xA0"],
                [':', '/', ' ', ' '],
                $normalized
            );

            // 空白をまとめる
            $normalized = preg_replace('/\s+/', ' ', trim($normalized));
            // 時刻のゼロ埋め（空白入りにも対応）
            $normalized = preg_replace_callback(
                '/\b(\d{1,2})\s*:\s*(\d{1,2})(?:\s*:\s*(\d{1,2}))?\b/',
                function ($m) {
                    if (isset($m[3])) {
                        return sprintf('%d:%02d:%02d', $m[1], $m[2], $m[3]);
                    }
                    return sprintf('%d:%02d', $m[1], $m[2]);
                },
                $normalized
            );
            $stringFormatYmdHms = '/^\s*(\d{1,4})\s*\/\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{2})\s*:\s*(\d{2})\s*$/';
            $stringFormatYmdHm = '/^\s*(\d{1,4})\s*\/\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{2})\s*$/';
            $stringFormatMdHms = '/^\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{2})\s*:\s*(\d{2})\s*$/';
            $stringFormatMdHm = '/^\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{2})\s*$/';
            $stringFormatHms = '/^\s*(\d{1,2})\s*:\s*(\d{2})\s*:\s*(\d{2})\s*$/';
            $stringFormatHm = '/^\s*(\d{1,2})\s*:\s*(\d{2})\s*$/';

            // 年月日時分秒
            if (preg_match($stringFormatYmdHms, $normalized, $m)) {
                $candidateIsTime = self::IS_SECOND_ALLOWED;
                $year = (int)$m[1];
                $month = (int)$m[2];
                $day = (int)$m[3];
                $hour = (int)$m[4];
                $minute = (int)$m[5];
                $second = (int)$m[6];
            // 年月日時分
            } elseif (preg_match($stringFormatYmdHm, $normalized, $m)) {
                $candidateIsTime = true;
                $year = (int)$m[1];
                $month = (int)$m[2];
                $day = (int)$m[3];
                $hour = (int)$m[4];
                $minute = (int)$m[5];
                $second = 0;
            // 月日時分秒
            } elseif (preg_match($stringFormatMdHms, $normalized, $m)) {
                $candidateIsTime = self::IS_SECOND_ALLOWED;
                $year = $initialYear;
                $month = (int)$m[1];
                $day = (int)$m[2];
                $hour = (int)$m[3];
                $minute = (int)$m[4];
                $second = (int)$m[5];
            // 月日時分
            } elseif (preg_match($stringFormatMdHm, $normalized, $m)) {
                $candidateIsTime = true;
                $year = $initialYear;
                $month = (int)$m[1];
                $day = (int)$m[2];
                $hour = (int)$m[3];
                $minute = (int)$m[4];
                $second = 0;
            // 時分秒
            } elseif (preg_match($stringFormatHms, $normalized, $m)) {
                $candidateIsTime = self::IS_SECOND_ALLOWED;
                $year = $initialYear;
                $month = $initialMonth;
                $day = $initialDay;
                $hour = (int)$m[1];
                $minute = (int)$m[2];
                $second = (int)$m[3];
            // 時分
            } elseif (preg_match($stringFormatHm, $normalized, $m)) {
                $candidateIsTime = true;
                $year = $initialYear;
                $month = $initialMonth;
                $day = $initialDay;
                $hour = (int)$m[1];
                $minute = (int)$m[2];
                $second = 0;
            }

            if($candidateIsTime === false){
                if($hasNoErrorCheck === false){
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::NO_TIME_FORMAT;
                    }//$timeParseErrorStatus
                }//$hasNoErrorCheck
            }

            if($hasNoErrorCheck === false){
                if ($year < 1 || $year > 9999) {
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::INVALID_YEAR;
                    }//$timeParseErrorStatus
                }
                if ($month < 1 || $month > 12) {
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::INVALID_MONTH;
                    }//$timeParseErrorStatus
                }
                $carbonDate = Carbon::create($year, $month, 1);
                $maxDay = $carbonDate->daysInMonth;

                if ($day < 1 || $day > $maxDay) {
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::INVALID_DAY;
                    }//$timeParseErrorStatus
                }

                if($hour < 0||$hour > 23){
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::INVALID_HOUR;
                    }//$timeParseErrorStatus
                }

                if($minute < 0||$minute > 59){
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::INVALID_MINUTE;
                    }//$timeParseErrorStatus
                }

                $errors = ['error_count' => 0];
                
                if($errors['error_count'] > 0){
                    if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                        $timeParseErrorStatus = TimeParseErrorStatus::NO_TIME_FORMAT;
                    }//$timeParseErrorStatus
                }
            }//$hasNoErrorCheck

        }//$stringValue

        $isTime = false;
        if($candidateIsTime === true){
            if($timeParseErrorStatus === TimeParseErrorStatus::UNDEFINED){
                $isTime = true;
            }//$timeParseErrorStatus
        }//$candidateIsTime

        if($isTime === true){
            $carbonTime = Carbon::create($year, $month, $day, $hour, $minute, $second);
        }else{//$isTime
            $carbonTime = null;
        }//$isTime

        $timeParseParamsValue->time_parse_error_status = $timeParseErrorStatus;
        $timeParseParamsValue->carbon_time = $carbonTime;

        return($timeParseParamsValue);
    }
}