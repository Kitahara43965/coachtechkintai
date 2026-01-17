<?php
namespace App\DTOs;

use Carbon\Carbon;

class TimetableList{
    public int $id = 0;
    public ?int $timetable_id = null;
    public bool $is_timetable_id_existence = false;
    public int $day = 0;
    public ?Carbon $referenced_at = null;
    public ?string $string_referenced_at_year_month_day_weekday = null;
    public ?string $string_referenced_at_lettered_year = null;
    public ?string $string_referenced_at_lettered_month_day = null;
    public ?Carbon $checkin_at = null;
    public ?Carbon $checkout_at = null;
    public int $total_break_time_second = 0;
    public int $total_working_time_second = 0;
    public ?string $string_attendance_list_checkin_at = null;
    public ?string $string_attendance_list_checkout_at = null;
    public ?string $string_attendance_detail_checkin_at = null;
    public ?string $string_attendance_detail_checkout_at = null;
    public ?string $string_total_break_time_minute = null;
    public ?string $string_total_working_time_minute = null;
    public ?string $string_timetable_user_name = null;
    public ?string $string_timetable_description = null;

    public ?Carbon $created_at = null;
    public ?Carbon $updated_at = null;

    public function __construct(array $data = []) {
        $carbonFields = [
            'referenced_at',
            'checkin_at',
            'checkout_at',
            'created_at',
            'updated_at'
        ];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (in_array($key, $carbonFields, true)) {
                    $this->$key = $value ? Carbon::parse($value) : null;
                } else {
                    $this->$key = $value;
                }
            }//property_exists($this, $key)
        }
    }
    
}//TimetableList