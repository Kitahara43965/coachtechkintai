<?php

namespace App\Constants;

final class TimeParseErrorStatus
{
    public const UNDEFINED = 0;
    public const NO_TIME_FORMAT = 1;
    public const INVALID_YEAR = 2;
    public const INVALID_MONTH = 3;
    public const INVALID_DAY = 4;
    public const INVALID_HOUR = 5;
    public const INVALID_MINUTE = 6;
    public const INVALID_SECOND = 7;
    

    public static function message($errorStatus)
    {
        switch ($errorStatus) {
            case self::UNDEFINED:
                return '時間の文字列です。';
            case self::NO_TIME_FORMAT:
                return '時間のフォーマットではありません。';
            case self::INVALID_YEAR:
                return '「年」は1以上9999以下で入力してください。';
            case self::INVALID_MONTH:
                return '「月」は1以上12以下で入力してください。';
            case self::INVALID_DAY:
                return '正しい「日」を入力してください。';
            case self::INVALID_HOUR:
                return '「時」は0以上23以下を入力してください。';
            case self::INVALID_MINUTE:
                return '「分」は0以上59以下で入力してください。';
            default:
                return '不明なステータスです。';
        }
    }
}