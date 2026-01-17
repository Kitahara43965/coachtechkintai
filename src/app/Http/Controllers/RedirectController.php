<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Constants\TimetableListType;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;

final class OriginalRedirectFunctionKind{
    public const UNDEFINED = 0;
    public const REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE = 1;
    public const REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST = 2;
    public const REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER = 3;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE = 4;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED = 5;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED = 6;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE = 7;
    public const REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST = 8;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF = 9;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN = 10;
}


final class RedirectFunctionKind{
    public const UNDEFINED = 0;
    public const REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE = 1;
    public const REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST = 2;
    public const REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER = 3;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE = 4;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED = 5;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED = 6;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE = 7;
    public const REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST = 8;
    public const REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF = 9;
    public const REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN = 10;
}


class RedirectController extends Controller
{
    public static function onRedirect($request,$originalRedirectFunctionKind, $id){

        $routeRedirect = null;
        if($originalRedirectFunctionKind === OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE){
            $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE;
        }else if($originalRedirectFunctionKind === OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else if($originalRedirectFunctionKind === OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else if($originalRedirectFunctionKind
             === OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE){
            $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE;
        }else if($originalRedirectFunctionKind
             === OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED){
            $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED;
        }else if($originalRedirectFunctionKind
             === OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED){
            $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED;
        }else if($originalRedirectFunctionKind
             === OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else if($originalRedirectFunctionKind === OriginalRedirectFunctionKind::REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else if($originalRedirectFunctionKind === OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else if($originalRedirectFunctionKind
             === OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN){
            if($id){
                $redirectFunctionKind = RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN;
            }else{//$id
                $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
            }//$id
        }else{//$originalRedirectFunctionKind
            $redirectFunctionKind = RedirectFunctionKind::UNDEFINED;
        }//$originalRedirectFunctionKind

        $routeRedirect = null;
        $attendanceDetailBladeShowFunctionKindMarker= 0;
        $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
        $attendanceListDateUpdateMarker = 0;
        $stampCorrectionRequestListIsAdmittedMarker = 0;
        $stampCorrectionRequestListIsAdmitted = false;

        if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE){
            $routeRedirect = route('attendanceList');
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST){
            $routeRedirect = route('attendanceDetail.id',['id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 1;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::ATTENDANCE_LIST;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER){
            $routeRedirect = route('attendanceDetail.id',['id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 1;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_USER;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE){
            $routeRedirect = route('admin.attendanceList');
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED){
            $routeRedirect = route('stampCorrectionRequestList');
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 0;
            $stampCorrectionRequestListIsAdmittedMarker = 1;
            $stampCorrectionRequestListIsAdmitted = false;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED){
            $routeRedirect = route('stampCorrectionRequestList');
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 0;
            $stampCorrectionRequestListIsAdmittedMarker = 1;
            $stampCorrectionRequestListIsAdmitted = true;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE){
            $routeRedirect = route('admin.attendanceStaff.id',['id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST){
            $routeRedirect = route('admin.attendance.id',['id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 1;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_LIST;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind === RedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF){
            $routeRedirect = route('admin.attendance.id',['id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 1;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID;
            $attendanceListDateUpdateMarker = 1;
        }else if($redirectFunctionKind
             === RedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN){
            $routeRedirect = route('stampCorrectionRequestApprove.attendanceCorrectRequestId',['attendance_correct_request_id'=>$id]);
            $attendanceDetailBladeShowFunctionKindMarker= 1;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN;
            $attendanceListDateUpdateMarker = 1;
        }else{//$redirectFunctionKind
            $routeRedirect = route('attendanceList');
            $attendanceDetailBladeShowFunctionKindMarker= 0;
            $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
            $attendanceListDateUpdateMarker = 0;
        }//$redirectFunctionKind


        if($attendanceDetailBladeShowFunctionKindMarker !== 0){
            session(['attendanceDetailBladeShowFunctionKind' => $attendanceDetailBladeShowFunctionKind]);
        }//$isAttendanceDetailBladeShowFunctionKind

        if($attendanceListDateUpdateMarker !== 0){
            session()->flash('attendanceListDate', [
                'redirectYear'  => $request->get('redirectYear'),
                'redirectMonth' => $request->get('redirectMonth'),
                'redirectDay'   => $request->get('redirectDay'),
            ]);
        }//$attendanceListDateUpdateMarker

        if($stampCorrectionRequestListIsAdmittedMarker !== 0){
            session(['stampCorrectionRequestListIsAdmitted'=> $stampCorrectionRequestListIsAdmitted]);
        }//$stampCorrectionRequestListIsAdmittedMarker

        return redirect($routeRedirect);

    }//onRedirect

    public function redirectToAttendanceListCalendarUpdate(Request $request)
    {
        $id = null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_LIST_CALENDAR_UPDATE;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }//


    public function redirectToAttendanceDetailViaAttendanceList(Request $request,$stringId)
    {
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_ATTENDANCE_LIST;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }


    public function redirectToAttendanceDetailViaStampCorrectionRequestListForUser(Request $request,$stringId)
    {
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ATTENDANCE_DETAIL_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_USER;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }

    public function redirectToAdminAttendanceListCalendarUpdate(
        Request $request
    ){
        $id = null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_LIST_CALENDAR_UPDATE;
        return $this->onRedirect($request, $originalRedirectFunctionKind,$id);
    }

    public function redirectToStampCorrectionRequestListNotAdmittedUpdate(
        Request $request
    ){
        $id = null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_NOT_ADMITTED;
        return $this->onRedirect($request, $originalRedirectFunctionKind,$id);
    }

    public function redirectToStampCorrectionRequestListAdmittedUpdate(
        Request $request
    ){
        $id = null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_LIST_ADMITTED;
        return $this->onRedirect($request, $originalRedirectFunctionKind,$id);
    }

    public function redirectToAdminAttendanceStaffCalendarUpdateId(
        Request $request,$stringId
    ){
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_STAFF_CALENDAR_UPDATE;
        return $this->onRedirect($request, $originalRedirectFunctionKind,$id);
    }


    public function redirectToAdminAttendanceViaAdminAttendanceList(Request $request,$stringId)
    {
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_AMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_LIST;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }

    public function redirectToAdminAttendanceViaAdminAttendanceStaff(Request $request,$stringId)
    {
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind = OriginalRedirectFunctionKind::REDIRECT_TO_ADMIN_ATTENDANCE_VIA_ADMIN_ATTENDANCE_STAFF;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }

    //stampCorrectionRequestApproveAttendanceCorrectRequestId

    public function redirectToStampCorrectionRequestApproveViaStampCorrectionRequestListForAdmin(Request $request,$stringId)
    {
        $id = $stringId ? (int)$stringId : null;
        $originalRedirectFunctionKind
             = OriginalRedirectFunctionKind::REDIRECT_TO_STAMP_CORRECTION_REQUEST_APPROVE_VIA_STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN;
        return self::onRedirect($request,$originalRedirectFunctionKind,$id);
    }
    
}
