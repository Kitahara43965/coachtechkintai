<?php
namespace App\DTOs;

use Carbon\Carbon;

class BreakTimeList{
    public int $id = 0;
    public ?int $break_time_id = null;
    public bool $is_break_time_id_existence = false;
    public ?Carbon $break_time_start_at = null;
    public ?Carbon $break_time_end_at = null;
    public ?string $string_attendance_detail_break_time_start_at = null;
    public ?string $string_attendance_detail_break_time_end_at = null;
    public ?string $string_attendance_detail_break_time_label = null;
    public ?Carbon $created_at = null;
    public ?Carbon $updated_at = null;

    public function __construct(array $data = []) {
        $carbonFields = [
            'break_time_start_at',
            'break_time_end_at',
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