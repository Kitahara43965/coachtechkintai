<?php

namespace App\Constants;

final class TimeFieldPairErrorStatus
{
    public const UNDEFINED = 0;
    public const ELEMENT_WRONG = 1;
    public const EMPTY_BREAK_TIME_START_FILLED_BREAK_TIME_END = 2;
    public const CHECKIN_CHECKOUT_REVERSE_ENDS = 3;
    public const BREAK_TIME_START_BREAK_TIME_END_REVERSE_ENDS = 4;
    public const CHECKIN_CHECKOUT_START_END_SAME = 5;
    public const BREAK_TIME_START_BREAK_TIME_END_START_END_SAME = 6;
    public const BREAK_TIME_START_DURATION_OVERFLOW = 7;
    public const BREAK_TIME_END_DURATION_OVERFLOW = 8;
    public const BREAK_TIME_OVERLAP = 9;

    public static function message($errorStatus)
    {
        switch ($errorStatus) {
            case self::UNDEFINED:
                return '入力が正常になされています。';
            case self::ELEMENT_WRONG:
                return '入力のいずれかに誤りがあります。';
            case self::EMPTY_BREAK_TIME_START_FILLED_BREAK_TIME_END:
                return '休憩開始時間を入力するか休憩終了時間を削除してください。';
            case self::CHECKIN_CHECKOUT_REVERSE_ENDS:
                return '出勤時間もしくは退勤時間が不適切な値です。';
            case self::BREAK_TIME_START_BREAK_TIME_END_REVERSE_ENDS:
                return '休憩開始時間もしくは休憩終了時間が不適切な値です。';
            case self::CHECKIN_CHECKOUT_START_END_SAME;
                return '出勤時間と退勤時間が同じです。';
            case self::BREAK_TIME_START_BREAK_TIME_END_START_END_SAME;
                return '休憩開始時間と休憩終了時間が同じです。';
            case self::BREAK_TIME_START_DURATION_OVERFLOW:
                return '休憩時間が不適切な値です。';
            case self::BREAK_TIME_END_DURATION_OVERFLOW:
                return '休憩時間もしくは退勤時間が不適切な値です。';
            case self::BREAK_TIME_OVERLAP:
                return '休憩時間が重なっています。';
            default:
                return '不明なステータスです。';
        }
    }
}