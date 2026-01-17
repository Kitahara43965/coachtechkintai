<?php
namespace App\DTOs;

use Carbon\Carbon;

class DraftBreakTimeDTO{
    public ?Carbon $break_time_start_at = null;
    public ?Carbon $break_time_end_at = null;
    public ?Carbon $pan_break_time_start_at = null;
    public ?Carbon $pan_break_time_end_at = null;
}//BreakTimeList