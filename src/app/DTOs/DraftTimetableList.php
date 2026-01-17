<?php
namespace App\DTOs;

use Carbon\Carbon;

class DraftTimetableList{
    public int $id = 0;
    public ?int $draft_timetable_id = null;
    public bool $is_draft_timetable_id_existence = false;
    public bool $draft_timetable_is_admitted = false;
    public ?string $string_draft_timetable_description = null;
    public ?Carbon $draft_timetable_created_at = null;
    public ?Carbon $checkin_at = null;
    public ?Carbon $checkout_at = null;
    public ?string $string_is_admitted = null;
    public ?string $string_draft_timetable_user_name = null;
    public ?string $string_stamp_correction_request_list_checkin_checkout = null;
    public ?string $string_stamp_correction_request_list_created_at = null;
    public ?Carbon $updated_at = null;
    public ?Carbon $created_at = null;
    public ?Carbon $referenced_at = null;
    public ?string $string_attendance_detail_checkin_at = null;
    public ?string $string_attendance_detail_checkout_at = null;
    public ?string $string_referenced_at_year_month_day_weekday = null;
    public ?string $string_referenced_at_lettered_year = null;
    public ?string $string_referenced_at_lettered_month_day = null;
    
    public function __construct(array $data = []) {
        $carbonFields = [
            'draft_timetable_created_at',
            'checkin_at',
            'checkout_at',
            'updated_at',
            'created_at',
            'referenced_at',
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