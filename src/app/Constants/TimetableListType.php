<?php

namespace App\Constants;

final class TimetableListType{
    public const UNDEFINED = 0;
    public const TIMETABLE_LIST = 1;
    public const DRAFT_TIMETABLE_LIST = 2;

    public static function toArray()
    {
        return [
            'UNDEFINED' => self::UNDEFINED,
            'TIMETABLE_LIST' => self::TIMETABLE_LIST,
            'DRAFT_TIMETABLE_LIST' => self::DRAFT_TIMETABLE_LIST,
        ];
    }

    public static function getName($timetableListType)
    {
        switch ($timetableListType) {
            case self::UNDEFINED:
                return 'UNDEFINED';
            case self::TIMETABLE_LIST:
                return 'TIMETABLE_LIST';
            case self::DRAFT_TIMETABLE_LIST:
                return 'DRAFT_TIMETABLE_LIST';
            default:
                return 'INVALID_TIMETABLE_LIST';
        }
    }
}//TimetableListType