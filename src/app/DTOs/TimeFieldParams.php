<?php
namespace App\DTOs;

use App\Constants\TimeFieldErrorStatus;
use Carbon\Carbon;

class TimeFieldParams{
    public ?Carbon $carbon_time = null;
    public int $time_parse_error_status = TimeFieldErrorStatus::UNDEFINED;
    public ?string $time_parse_error_message = null;
    public int $time_field_error_status = TimeFieldErrorStatus::UNDEFINED;
    public ?string $time_field_error_message = null;
    public ?string $string_value = null;
    public bool $has_value = false;
    public ?string $string_field_name = null;
}//TimeStringParams