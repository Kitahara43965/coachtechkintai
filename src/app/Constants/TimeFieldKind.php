<?php

namespace App\Constants;

use App\Constants\FieldType;

final class TimeFieldKind
{
    const UNDEFINED = 0;
    const CHECKIN_AT = 1;
    const CHECKOUT_AT = 2;
    const BREAK_TIME_START_AT = 3;
    const BREAK_TIME_END_AT = 4;
    const DESCRIPTION = 5;

    public static function fieldName($kind)
    {
        switch ($kind) {
            case self::CHECKIN_AT:
                return 'draft_timetable_list_checkin_at';
            case self::CHECKOUT_AT:
                return 'draft_timetable_list_checkout_at';
            case self::BREAK_TIME_START_AT:
                return 'draft_timetable_list_break_time_start_at';
            case self::BREAK_TIME_END_AT:
                return 'draft_timetable_list_break_time_end_at';
            case self::DESCRIPTION:
                return 'draft_timetable_list_description';
            default:
                return null;
        }
    }

    public static function fieldNames()
    {
        return array_values(array_filter([
            self::fieldName(self::CHECKIN_AT),
            self::fieldName(self::CHECKOUT_AT),
            self::fieldName(self::BREAK_TIME_START_AT),
            self::fieldName(self::BREAK_TIME_END_AT),
            self::fieldName(self::DESCRIPTION),
        ]));
    }

}