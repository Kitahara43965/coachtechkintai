<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\DummyTimetableListController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableListController;
use App\Http\Controllers\DraftTimetableController;
use App\Http\Controllers\DraftTimetableListController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyEmailController;

//AdminAttendanceStaff

Route::get('/register', [ShowController::class, 'register'])
    ->name('register');
Route::post('/register', [RegisterController::class, 'registerStore'])
    ->name('registerStore');

Route::get('/login', [ShowController::class, 'userLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'loginStore'])
    ->name('loginStore');

Route::get('/admin/login', [ShowController::class, 'adminLogin'])
    ->name('admin.login');


Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [ShowController::class, 'showEmailVerification'])
        ->name('verification.notice');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::post('/verify-email', [VerifyEmailController::class,'verifyEmail'])
        ->name('verification.manual');

    Route::post('/resend-email', [VerifyEmailController::class,'resendEmail'])
        ->name('resendEmail');

});

Route::middleware(['auth', 'signed', 'throttle:6,1'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class,'emailVerifyIdHash'])
        ->name('verification.verify');

});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/attendance', [ShowController::class, 'index'])
        ->name('index');
    Route::get('/attendance/list', [ShowController::class, 'attendanceList'])
        ->name('attendanceList');

    Route::get('/attendance/detail/{id}', [ShowController::class, 'attendanceDetailId'])
        ->name('attendanceDetail.id');
        
    Route::get('/redirect-to-attendance-detail-via-attendance-list/{id}', 
        [RedirectController::class, 'redirectToAttendanceDetailViaAttendanceList'])
        ->name('redirectToAttendanceDetailViaAttendanceList.id');

     Route::get('/redirect-to-attendance-detail-via-stamp-correction-request-list-for-user/{id}', 
        [RedirectController::class, 'redirectToAttendanceDetailViaStampCorrectionRequestListForUser'])
        ->name('redirectToAttendanceDetailViaStampCorrectionRequestListForUser.id');

    Route::post('/update-dummy-timetable-list', [DummyTimetableListController::class, 'updateDummyTimetableList'])
        ->name('updateDummyTimetableList');

    Route::post("/draft-timetable-modal",[DraftTimetableController::class, 'draftTimetableModal'])
        ->name('draftTimetableModal');
    
    Route::post("/draft-timetable-submit",[DraftTimetableController::class, 'draftTimetableSubmit'])
        ->name('draftTimetableSubmit');

    Route::post('/draft-timetable-update', [DraftTimetableController::class, 'draftTimetableUpdate'])
        ->name('draftTimetableUpdate');

    Route::post('/draft-timetable-replace', [DraftTimetableController::class, 'draftTimetableReplace'])
        ->name('draftTimetableReplace');

    Route::post('/checkin', [TimetableController::class, 'checkin'])
        ->name('checkin');
    Route::post('/break-time-start', [TimetableController::class, 'breakTimeStart'])
        ->name('breakTimeStart');


    Route::get('/redirect-to-attendance-list-calendar-update'
        ,[RedirectController::class, 'redirectToAttendanceListCalendarUpdate'])
        ->name('redirectToAttendanceListCalendarUpdate');

});

Route::middleware(['auth','verified','role:admin'])->group(function () {

    Route::get('/admin/attendance/list', [ShowController::class, 'adminAttendanceList'])
        ->name('admin.attendanceList');

    Route::get('/admin/attendance/{id}', [ShowController::class, 'adminAttendanceId'])
        ->name('admin.attendance.id');

    Route::get('/admin/staff/list', [ShowController::class, 'adminStaffList'])
        ->name('admin.staffList');

    Route::get('/admin/attendance/staff/{id}', [ShowController::class, 'adminAttendanceStaffId'])
        ->name('admin.attendanceStaff.id');

     Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}',
        [ShowController::class, 'stampCorrectionRequestApproveAttendanceCorrectRequestId'])
        ->name('stampCorrectionRequestApprove.attendanceCorrectRequestId');

    Route::get('/redirect-to-admin-attendance-via-admin-attendance-list/{id}', 
        [RedirectController::class, 'redirectToAdminAttendanceViaAdminAttendanceList'])
        ->name('redirectToAdminAttendanceViaAdminAttendanceList.id');

    Route::get('/redirect-to-admin-attendance-via-admin-attendance-staff/{id}', 
        [RedirectController::class, 'redirectToAdminAttendanceViaAdminAttendanceStaff'])
        ->name('redirectToAdminAttendanceViaAdminAttendanceStaff.id');

    Route::get('/redirect-to-stamp-correction-request-approve-via-stamp-correction-request-list-for-admin/{attendance_correct_request_id}', 
        [RedirectController::class, 'redirectToStampCorrectionRequestApproveViaStampCorrectionRequestListForAdmin'])
        ->name('redirectToStampCorrectionRequestApproveViaStampCorrectionRequestListForAdmin.id');

    Route::get('/redirect-to-admin-attendance-list-calendar-update',
        [RedirectController::class, 'redirectToAdminAttendanceListCalendarUpdate'])
        ->name('redirectToAdminAttendanceListCalendarUpdate');

    Route::get('/redirect-to-admin-attendance-staff-calendar-update/{id}',
        [RedirectController::class, 'redirectToAdminAttendanceStaffCalendarUpdateId'])
        ->name('redirectToAdminAttendanceStaffCalendarUpdate.id');
});

Route::middleware(['auth','verified', 'role:admin,user'])->group(function () {
    Route::get('/redirect-to-stamp-correction-request-list-not-admitted-update'
        ,[RedirectController::class, 'redirectToStampCorrectionRequestListNotAdmittedUpdate'])
        ->name('redirectToStampCorrectionRequestListNotAdmittedUpdate');

    Route::get('/redirect-to-stamp-correction-request-list-admitted-update'
        ,[RedirectController::class, 'redirectToStampCorrectionRequestListAdmittedUpdate'])
        ->name('redirectToStampCorrectionRequestListAdmittedUpdate');

    Route::get('/stamp-correction-request/list',[ShowController::class, 'stampCorrectionRequestList'])
        ->name('stampCorrectionRequestList');
});

