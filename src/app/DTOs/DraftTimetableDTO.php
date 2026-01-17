<?php
namespace App\DTOs;

use Carbon\Carbon;

class DraftTimetableDTO{
    public ?Carbon $checkin_at = null;
    public ?Carbon $checkout_at = null;
    public ?Carbon $pan_checkin_at = null;
    public ?Carbon $pan_checkout_at = null;
    public ?string $description = null;
}//BreakTimeList