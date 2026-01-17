<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Constants\UserRole;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Timetable;
use App\Models\BreakTime;
use App\Models\DraftTimetable;
use App\Models\DraftBreakTime;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        $carbonNow = Carbon::now();
        $carbonNowSubHour = $carbonNow->copy()->subHour();
        $carbonNowSubMinutes30 = $carbonNow->copy()->subMinutes(30);

        $admin = User::create([
            'role' => UserRole::ADMIN,
            'name' => 'Dummy Admin',
            'email' => 'admintest@mail.com',
            'password' => 'admintest',
        ]);

        $adminTimetable = Timetable::create([
            'user_id' => $admin->id,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです。'
        ]);

        $adminBreakTime = BreakTime::create([
            'timetable_id' => $adminTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);

        $adminFirstDraftTimetable = DraftTimetable::create([
            'user_id' => $admin->id,
            'is_admitted' => false,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです(未承認)。'
        ]);

        $adminFirstDraftBreakTime = DraftBreakTime::create([
            'draft_timetable_id' => $adminFirstDraftTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);
        
        $adminSecondDraftTimetable = DraftTimetable::create([
            'user_id' => $admin->id,
            'is_admitted' => true,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです(承認済み)。'
        ]);

        $adminSecondDraftBreakTime = DraftBreakTime::create([
            'draft_timetable_id' => $adminSecondDraftTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);


        $user = User::create([
            'role' => UserRole::USER,
            'name' => 'Dummy User',
            'email' => 'usertest@mail.com',
            'password' => 'usertest',
        ]);


        $userTimetable = Timetable::create([
            'user_id' => $user->id,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです。'
        ]);

        $userBreakTime = BreakTime::create([
            'timetable_id' => $userTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);

        $userFirstDraftTimetable = DraftTimetable::create([
            'user_id' => $user->id,
            'is_admitted' => false,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです(未承認)。'
        ]);

        $userFirstDraftBreakTime = DraftBreakTime::create([
            'draft_timetable_id' => $userFirstDraftTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);
        
        $userSecondDraftTimetable = DraftTimetable::create([
            'user_id' => $user->id,
            'is_admitted' => true,
            'checkin_at' => $carbonNowSubHour,
            'checkout_at' => $carbonNow,
            'description' => 'ダミーです(承認済み)。'
        ]);

        $userSecondDraftBreakTime = DraftBreakTime::create([
            'draft_timetable_id' => $userSecondDraftTimetable->id,
            'break_time_start_at' => $carbonNowSubMinutes30,
            'break_time_end_at' => $carbonNow,
        ]);




    }
}
