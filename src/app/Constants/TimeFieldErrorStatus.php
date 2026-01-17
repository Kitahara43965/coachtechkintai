<?php

namespace App\Constants;

final class TimeFieldErrorStatus
{
    public const UNDEFINED = 0;
    public const TIME_PARSE = 1;
    public const TIME_VALUE_NO_VALUE = 2;
    public const DESCRIPTION_NO_VALUE = 3;
    public const TOO_LONG_STRING_VALUE = 4;
    public const FUTURE_TIME = 5;

    public const MAX_STRING_VALUE_CHAR_NUMBER = 255;

    public static function message($errorStatus)
    {
        switch ($errorStatus) {
            case self::UNDEFINED:
                return '有効な入力値です。';
            case self::TIME_PARSE:
                return '時間の表記に誤りがあります。';
            case self::TIME_VALUE_NO_VALUE:
                return '時間を記入してください。';
            case self::DESCRIPTION_NO_VALUE:
                return '備考を記入してください。';
            case self::TOO_LONG_STRING_VALUE:
                return self::MAX_STRING_VALUE_CHAR_NUMBER.'文字以下で入力してください。';
            case self::FUTURE_TIME:
                return '現在よりも先の時刻は入力できません。';
            default:
                return '不明なステータスです。';
        }
    }
}