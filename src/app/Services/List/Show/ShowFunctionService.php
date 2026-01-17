<?php

namespace App\Services\List\Show;

use Illuminate\Support\Facades\Auth;
use App\Models\Timetable;
use App\Models\DraftTimetable;
use Carbon\Carbon;
use App\DTOs\TimetableList;
use App\Constants\TimetableListType;
use App\Constants\UserRole;
use App\Constants\TimetableListCarbonDayKind;
use App\Models\User;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;
use App\Constants\ShowFunctionKinds\OriginalShowFunctionKind;
use App\Constants\ShowFunctionKinds\PostedUserStatus;

class ShowFunctionService{
    public static function getShowFunctionKind(
        $originalShowFunctionKind,
        $authUserId,
        $attendanceDetailBladeShowFunctionKind,
        $adminAttendanceStaffIdUserId,
        $loginBladeUserRole
    ){

        $authUser = User::find($authUserId);

        $showFunctionKind = ShowFunctionKind::UNDEFINED;
        if($originalShowFunctionKind === OriginalShowFunctionKind::USER_LOGIN){
            $showFunctionKind = ShowFunctionKind::USER_LOGIN;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_LOGIN){
            $showFunctionKind = ShowFunctionKind::ADMIN_LOGIN;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::REGISTER){
            $showFunctionKind = ShowFunctionKind::REGISTER;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::SHOW_EMAIL_VERIFICATION){
            if($loginBladeUserRole === UserRole::ADMIN){
                $showFunctionKind = ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_ADMIN;
            }else{//$loginBladeUserRole
                $showFunctionKind = ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_USER;
            }//$loginBladeUserRole
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ATTENDANCE){
            $showFunctionKind = ShowFunctionKind::ATTENDANCE;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ATTENDANCE_LIST){
            $showFunctionKind = ShowFunctionKind::ATTENDANCE_LIST;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ATTENDANCE_DETAIL_ID){
            if($attendanceDetailBladeShowFunctionKind === ShowFunctionKind::ATTENDANCE_LIST){
                $showFunctionKind = ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_ATTENDANCE_LIST;
            }else if($attendanceDetailBladeShowFunctionKind === ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
                $showFunctionKind = ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER;
            }else{//$attendanceDetailBladeShowFunctionKind
                $showFunctionKind = ShowFunctionKind::ATTENDANCE;
            }//$attendanceDetailBladeShowFunctionKind
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST){
            if($authUser){
                if($authUser->role === UserRole::ADMIN){
                    $showFunctionKind = ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN;
                }else{//$authUser->role
                    $showFunctionKind = ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_USER;
                }//$authUser
            }else{//$authUser
                $showFunctionKind = ShowFunctionKind::ATTENDANCE;
            }//$authUser
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_ATTENDANCE_LIST){
            $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_LIST;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_ATTENDANCE_ID){
            if($attendanceDetailBladeShowFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST){
                $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_LIST;
            }else if($attendanceDetailBladeShowFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
                if($adminAttendanceStaffIdUserId){
                    $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_STAFF_ID;
                }else{//$adminAttendanceStaffIdUserId
                    $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_LIST;
                }//$adminAttendanceStaffIdUserId
            }else{//$attendanceDetailBladeShowFunctionKind
                $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_LIST;
            }//$attendanceDetailBladeShowFunctionKind
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_STAFF_LIST){
            $showFunctionKind = ShowFunctionKind::ADMIN_STAFF_LIST;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
            $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::STAMP_CORRECTION_REQUEST_APPROVE_ATTENDANCE_CORRECT_REQUEST_ID){
            $showFunctionKind = ShowFunctionKind::STAMP_CORRECTION_REQUEST_APPROVE_ATTENDANCE_CORRECT_REQUEST_ID;
        }else{//$originalShowFunctionKind
            if($loginBladeUserRole === UserRole::ADMIN){
                $showFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_LIST;
            }else{
                $showFunctionKind = ShowFunctionKind::ATTENDANCE;
            }
        }//$originalShowFunctionKind

        return($showFunctionKind);
        
    }//getReturnedFileProperties

    public static function getReturnedFileProperties(
        $showFunctionKind,
        $id,
        $authUserId,
        $adminAttendanceStaffIdUserId
    ){
        $returnedBladeFile = null;
        $redirectTimetableListType = TimetableListType::UNDEFINED;
        $newId = null;
        $isMultipleFunctionHeader = false;
        $bladeUserRole = UserRole::UNDEFINED;
        $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
        $dummyTimetableRestrictedUserId = null;
        $isTimetableListUserDefined = false;
        $isDraftTimetableListUserDefined = false;
        $postedUserStatus = PostedUserStatus::UNDEFINED;

        if($showFunctionKind === ShowFunctionKind::USER_LOGIN){
            $returnedBladeFile = 'auth.login';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = false;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_LOGIN){
            $returnedBladeFile = 'auth.login';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = false;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::REGISTER){
            $returnedBladeFile = 'auth.register';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = false;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_USER){
            $returnedBladeFile = 'auth.verify-email';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = false;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_ADMIN){
            $returnedBladeFile = 'auth.verify-email';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = false;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ATTENDANCE){
            $returnedBladeFile = 'index';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ATTENDANCE_LIST){
            $returnedBladeFile = 'attendance-list';
            $redirectTimetableListType = TimetableListType::TIMETABLE_LIST;
            $newId = null;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_MONTH;
            $dummyTimetableRestrictedUserId = $authUserId;
            $isTimetableListUserDefined = true;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_ATTENDANCE_LIST){
            $returnedBladeFile = 'attendance-detail';
            $redirectTimetableListType = TimetableListType::TIMETABLE_LIST;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_MONTH;
            $dummyTimetableRestrictedUserId = $authUserId;
            $isTimetableListUserDefined = true;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ATTENDANCE_DETAIL_ID_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
            $returnedBladeFile = 'attendance-detail';
            $redirectTimetableListType = TimetableListType::DRAFT_TIMETABLE_LIST;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_MONTH;
            $dummyTimetableRestrictedUserId = $authUserId;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = true;
        }else if($showFunctionKind === ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
            $returnedBladeFile = 'stamp-correction-request-list';
            $redirectTimetableListType = TimetableListType::DRAFT_TIMETABLE_LIST;
            $newId = null;
            $postedUserStatus = PostedUserStatus::AUTH_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::USER;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = $authUserId;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = true;
        }else if($showFunctionKind === ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN){
            $returnedBladeFile = 'stamp-correction-request-list';
            $redirectTimetableListType = TimetableListType::DRAFT_TIMETABLE_LIST;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST){
            $returnedBladeFile = 'attendance-list';
            $redirectTimetableListType = TimetableListType::TIMETABLE_LIST;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_DAY;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_LIST){
            $returnedBladeFile = 'attendance-detail';
            $redirectTimetableListType = TimetableListType::TIMETABLE_LIST;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::CURRENT_TIMETABLE_LIST_TIMETABLE_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_DAY;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_ID_VIA_ADMIN_ATTENDANCE_STAFF_ID){
            $returnedBladeFile = 'attendance-detail';
            $redirectTimetableListType = TimetableListType::TIMETABLE_LIST;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::ADMIN_ATTENDANCE_STAFF_ID_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_MONTH;
            $dummyTimetableRestrictedUserId = $adminAttendanceStaffIdUserId;
            $isTimetableListUserDefined = true;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_STAFF_LIST){
            $returnedBladeFile = 'admin-staff-list';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = null;
            $postedUserStatus = PostedUserStatus::UNDEFINED;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::UNDEFINED;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
            $returnedBladeFile = 'attendance-list';
            $redirectTimetableListType = TimetableListType::UNDEFINED;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::NEW_ID_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_MONTH;
            $dummyTimetableRestrictedUserId = $newId;
            $isTimetableListUserDefined = true;
            $isDraftTimetableListUserDefined = false;
        }else if($showFunctionKind === ShowFunctionKind::STAMP_CORRECTION_REQUEST_APPROVE_ATTENDANCE_CORRECT_REQUEST_ID){
            $returnedBladeFile = 'attendance-detail';
            $redirectTimetableListType = TimetableListType::DRAFT_TIMETABLE_LIST;
            $newId = $id;
            $postedUserStatus = PostedUserStatus::CURRENT_DRAFT_TIMETABLE_LIST_DRAFT_TIMETABLE_USER;
            $isMultipleFunctionHeader = true;
            $bladeUserRole = UserRole::ADMIN;
            $timetableListCarbonDayKind = TimetableListCarbonDayKind::SELECTED_DAY;
            $dummyTimetableRestrictedUserId = null;
            $isTimetableListUserDefined = false;
            $isDraftTimetableListUserDefined = false;
        }//$showFunctionKind

        $returnedFileProperties = [
            "returnedBladeFile" => $returnedBladeFile,
            "postedUserStatus" => $postedUserStatus,
            "isMultipleFunctionHeader" => $isMultipleFunctionHeader,
            "bladeUserRole" => $bladeUserRole,
            "timetableListCarbonDayKind" => $timetableListCarbonDayKind,
            "dummyTimetableRestrictedUserId" => $dummyTimetableRestrictedUserId,
            "isTimetableListUserDefined" => $isTimetableListUserDefined,
            "isDraftTimetableListUserDefined" => $isDraftTimetableListUserDefined,
            "redirectTimetableListType" => $redirectTimetableListType,
            "newId" => $newId,
        ];

        return($returnedFileProperties);
    }

}//ShowFunctionService