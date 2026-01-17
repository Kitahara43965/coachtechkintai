<?php
namespace App\DTOs;

use App\Constants\TimeParseErrorStatus;
use Carbon\Carbon;

class TimeParseParams{
    public ?string $string_field_name = null;
    public ?string $string_value = null;
    public int $time_parse_error_status = TimeParseErrorStatus::UNDEFINED;
    public ?string $time_parse_error_message = null;
    public ?Carbon $carbon_time = null;
}//TimeStringParams