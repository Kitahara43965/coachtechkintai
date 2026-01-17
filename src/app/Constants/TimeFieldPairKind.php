<?php

namespace App\Constants;

final class TimeFieldPairKind
{
    const UNDEFINED = 0;
    const CHECKIN_CHECKOUT = 1;
    const BREAK_TIME_START_BREAK_TIME_END = 2;

    public static  function fieldName($kind)
    {
        switch ($kind) {
            case self::CHECKIN_CHECKOUT:
                return 'draft_timetable_list_checkin_checkout';
            case self::BREAK_TIME_START_BREAK_TIME_END:
                return 'draft_timetable_list_break_time_start_break_time_end';
            default:
                return null;
        }
    }


    public static function fieldNames()
    {
        return array_values(array_filter([
            self::fieldName(self::CHECKIN_CHECKOUT),
            self::fieldName(self::BREAK_TIME_START_BREAK_TIME_END),
        ]));
    }
}