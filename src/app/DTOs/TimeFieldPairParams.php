<?php
namespace App\DTOs;

use App\Constants\TimeFieldErrorStatus;
use App\Constants\TimeFieldPairErrorStatus;
use Carbon\Carbon;

class TimeFieldPairParams{
    public int $time_field_pair_error_status = TimeFieldPairErrorStatus::UNDEFINED;
    public ?string $time_parse_pair_error_message = null;
    public ?string $string_field_name = null;
    public ?Carbon $start_time_at = null;
    public ?Carbon $end_time_at = null;
    public int $start_time_at_time_field_error_status = TimeFieldErrorStatus::UNDEFINED;
    public int $end_time_at_time_field_error_status = TimeFieldErrorStatus::UNDEFINED;
    public ?Carbon $pan_start_time_at = null;
    public ?Carbon $pan_end_time_at = null;
}//TimeStringParams