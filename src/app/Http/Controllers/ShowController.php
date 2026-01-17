<?php

namespace App\Http\Controllers;
use App\Services\List\Show\ShowFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Timetable;
use App\Models\DraftTimetable;
use Carbon\Carbon;
use App\Http\Controllers\TimetableController;
use App\Services\List\TimetableListService;
use App\Services\List\DraftTimetableListService;
use App\Services\List\TimetableListToolService;
use App\Services\List\DraftTimetableListToolService;
use App\Services\List\DummyBreakTimeListService;
use App\DTOs\TimetableList;
use App\Constants\TimeParseErrorStatus;
use App\Constants\TimetableListType;
use App\Services\Time\LocalizedCarbonService;
use App\Constants\TimeFieldKind;
use App\Constants\TimeFieldPairKind;
use App\Constants\UserRole;
use App\Constants\TimetableListCarbonDayKind;
use App\Models\User;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;
use App\Constants\ShowFunctionKinds\OriginalShowFunctionKind;
use App\Services\View\DraftTimetableModalButtonService;
use App\Constants\ShowFunctionKinds\PostedUserStatus;


class ShowController extends Controller
{
    public static function getDummyTimetableListsProperties(
        $timetableListCarbonDayKind,
        $selectedYear,
        $selectedMonth,
        $selectedDay,
        $stampCorrectionRequestListIsAdmitted,
        $dummyTimetableRestrictedUser,
        $isTimetableListUserDefined,
        $isDraftTimetableListUserDefined,
        $redirectTimetableListType,
        $newId,
        $carbonNow,
    ){
        $currentTimetableList = null;
        $currentDraftTimetableList = null;
        $currentTimetableListTimetableId = null;
        $currentDraftTimetableListDraftTimetableId = null;
        $timetableLists = null;
        $draftTimetableLists = null;
        $breakTimeLists = null;
        $draftBreakTimeLists = null;
        $currentDummyTimetableListReferencedAt = null;

        $currentTimetableListTimetableUserId = null;
        $currentDraftTimetableListDraftTimetableUserId = null;

        $carbonStartDay = null;
        $carbonEndDay = null;
        $isDayCompensation = false;
        if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH){
            $carbonStartDay = Carbon::create($selectedYear, $selectedMonth, 1)->startOfDay();
            $carbonEndDay = $carbonStartDay->copy()->endOfMonth();
            $isDayCompensation = true;
        }else if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY){
            $carbonStartDay = LocalizedCarbonService::create($selectedYear, $selectedMonth,$selectedDay,0,0,0,'Asia/Tokyo');
            $carbonEndDay = $carbonStartDay->copy();
            $isDayCompensation = false;
        }//$timetableListCarbonDayKind

        $timetableOrderKey = 'checkin_at';
        $timetableOrder = 'asc';
        $timetableListUser = null;
        if($isTimetableListUserDefined === true){
            $timetableListUser = $dummyTimetableRestrictedUser;
        }
        $timetableLists = TimetableListService::getAscendingTimetableLists(
            $carbonStartDay,
            $carbonEndDay,
            $timetableListUser,
            $timetableOrderKey,
            $timetableOrder,
            $carbonNow,
            $isDayCompensation
        );

        $draftTimetableOrderKey = "created_at";
        $draftTimetableOrder = "asc";
        $draftTimetableListUser = null;
        if($isDraftTimetableListUserDefined === true){
            $draftTimetableListUser = $dummyTimetableRestrictedUser;
        }

        $draftTimetableLists = DraftTimetableListService::getAscendingDraftTimetableListsFromUser(
            $draftTimetableListUser,
            $stampCorrectionRequestListIsAdmitted,
            $draftTimetableOrderKey,
            $draftTimetableOrder,
            $carbonNow
        );

        if($redirectTimetableListType === TimetableListType::TIMETABLE_LIST){

            $currentTimetableListNumber = TimetableListToolService::getTimetableListNumberFromTimetableListId(
                $newId,
                $timetableLists
            );
            $currentTimetableList = TimetableListToolService::getTimetableListFromTimetableListNumber(
                $currentTimetableListNumber,
                $timetableLists
            );

            $currentTimetableListTimetableUserId = TimetableListToolService::getTimetableListTimetableUserId($currentTimetableList);

            $currentTimetableListTimetableId = $currentTimetableList ? $currentTimetableList->timetable_id : null;

            $currentDummyTimetableListReferencedAt = $currentTimetableList ? $currentTimetableList->referenced_at : null;

            $orderKey = "break_time_start_at";
            $order = "asc";

            $breakTimeLists = DummyBreakTimeListService::getAscendingDummyBreakTimeListsFromDummyTimetableList(
                $currentTimetableList,
                $orderKey,
                $order,
                $carbonNow
            );

        }else if($redirectTimetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){

            $currentDraftTimetableListNumber = DraftTimetableListToolService::getDraftTimetableListNumberFromDraftTimetableListId(
                $newId,
                $draftTimetableLists
            );

            $currentDraftTimetableList = DraftTimetableListToolService::getDraftTimetableListFromDraftTimetableListNumber(
                $currentDraftTimetableListNumber,
                $draftTimetableLists
            );

            $currentDraftTimetableListDraftTimetableUserId
                 = DraftTimetableListToolService::getDraftTimetableListDraftTimetableUserId($currentDraftTimetableList);

            $currentDraftTimetableListDraftTimetableId = $currentDraftTimetableList ? $currentDraftTimetableList->draft_timetable_id : null;

            $currentDummyTimetableListReferencedAt = $currentDraftTimetableList ? $currentDraftTimetableList->referenced_at : null;
            
            $orderKey = "break_time_start_at";
            $order = "asc";

            $draftBreakTimeLists = DummyBreakTimeListService::getAscendingDummyBreakTimeListsFromDummyTimetableList(
                $currentDraftTimetableList,
                $orderKey,
                $order,
                $carbonNow
            );

        }//$redirectTimetableListType

        $dummyTimetableListsProperties = [
            "currentTimetableList" => $currentTimetableList,
            "currentDraftTimetableList" => $currentDraftTimetableList,
            "currentTimetableListTimetableId" => $currentTimetableListTimetableId,
            "currentDraftTimetableListDraftTimetableId" => $currentDraftTimetableListDraftTimetableId,
            "currentTimetableListTimetableUserId" => $currentTimetableListTimetableUserId,
            "currentDraftTimetableListDraftTimetableUserId" => $currentDraftTimetableListDraftTimetableUserId,
            "breakTimeLists" => $breakTimeLists,
            "draftBreakTimeLists" => $draftBreakTimeLists,
            "timetableLists" => $timetableLists,
            "draftTimetableLists" => $draftTimetableLists,
            "currentDummyTimetableListReferencedAt" => $currentDummyTimetableListReferencedAt,
        ];

        return($dummyTimetableListsProperties);

    }//getDummyTimetableListsProperties

    public function onCreate(Request $request, $originalShowFunctionKind, $id){

        $authUser = Auth::user();
        $authUserId = $authUser ? $authUser->id : null;

        $loginBladeUserRole = UserRole::UNDEFINED;
        if(session()->has('loginBladeUserRole')){
            $loginBladeUserRole = session('loginBladeUserRole');
        }
        
        $attendanceDetailBladeShowFunctionKind = ShowFunctionKind::UNDEFINED;
        if(session()->has('attendanceDetailBladeShowFunctionKind')){
            $attendanceDetailBladeShowFunctionKind = session('attendanceDetailBladeShowFunctionKind');
        }

        $adminAttendanceStaffIdUserId = null;
        if(session()->has('adminAttendanceStaffIdUserId')){
            $adminAttendanceStaffIdUserId = session('adminAttendanceStaffIdUserId');
        }

        $stampCorrectionRequestListIsAdmitted = false;
        if(session()->has('stampCorrectionRequestListIsAdmitted')){
            $stampCorrectionRequestListIsAdmitted = session('stampCorrectionRequestListIsAdmitted');
        }

        $carbonNow = Carbon::now();
        $attendanceListDate = session('attendanceListDate') ?? [];
        $selectedYear = $attendanceListDate['redirectYear'] ?? $carbonNow->year;
        $selectedMonth = $attendanceListDate['redirectMonth'] ?? $carbonNow->month;
        $selectedDay = $attendanceListDate['redirectDay'] ?? $carbonNow->day;
        $date = Carbon::create($selectedYear, $selectedMonth, $selectedDay);

        // 前月・翌月
        $previousMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        // 月の情報
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth   = $date->copy()->endOfMonth();

        $previousDay = $date->copy()->subDay();
        $nextDay = $date->copy()->addDay();

        $showFunctionKinds = ShowFunctionKind::toArray();
        $showFunctionKind = ShowFunctionService::getShowFunctionKind(
            $originalShowFunctionKind,
            $authUserId,
            $attendanceDetailBladeShowFunctionKind,
            $adminAttendanceStaffIdUserId,
            $loginBladeUserRole
        );

        $returnedFileProperties = ShowFunctionService::getReturnedFileProperties(
            $showFunctionKind,
            $id,
            $authUserId,
            $adminAttendanceStaffIdUserId
        );
        $returnedBladeFile = $returnedFileProperties["returnedBladeFile"];
        $postedUserStatus = $returnedFileProperties["postedUserStatus"];
        $isMultipleFunctionHeader = $returnedFileProperties["isMultipleFunctionHeader"];
        $bladeUserRole = $returnedFileProperties["bladeUserRole"];
        $timetableListCarbonDayKind = $returnedFileProperties["timetableListCarbonDayKind"];
        $redirectTimetableListType = $returnedFileProperties["redirectTimetableListType"];
        $dummyTimetableRestrictedUserId = $returnedFileProperties["dummyTimetableRestrictedUserId"];
        $dummyTimetableRestrictedUser = User::find($dummyTimetableRestrictedUserId);
        $isTimetableListUserDefined = $returnedFileProperties["isTimetableListUserDefined"];
        $isDraftTimetableListUserDefined = $returnedFileProperties["isDraftTimetableListUserDefined"];
        $newId = $returnedFileProperties["newId"];

        session(['loginBladeUserRole' => $bladeUserRole]);

        if($originalShowFunctionKind === OriginalShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
            session(['adminAttendanceStaffIdUserId' => $newId]);
        }//showFunctionKind


        $checkinButtonId = 'checkin-button-id';
        $breakTimeStartButtonId = 'break-time-start-button-id';
        $draftTimetableModalButtonId = "draft-timetable-modal-button-id";
        $draftTimetableSubmitButtonId = "draft-timetable-submit-button-id";
        $draftTimetableCloseButtonId = "draft-timetable-close-button-id";

        $currentYearMonthDayWeekdayId = 'current-year-month-day-weekday-id';
        $currentHourMinuteId = 'current-hour-minute-id';
        $currentAttendanceListCheckinAtId = 'current-checkin-at-id';
        $currentAttendanceListCheckoutAtId = 'current-checkout-at-id';
        $currentTotalBreakTimeMinuteId = 'current-total-break-time-minute-id';
        $currentTotalWorkingTimeMinuteId = 'current-total-working-time-minute-id';


        $environmentStatusId = 'environment-status-id';
        $invisibleAppendingClass = "invisible";
        $checkinButtonClass = "index-checkin-button";
        $breakTimeStartButtonClass = "index-break-time-start-button";

        $draftTimetableModalButtonClass = "draft-timetable-modal-button";
        $draftTimetableModalButtonMessageClass = "draft-timetable-modal-button-message";
        $draftTimetableSubmitButtonClass = "draft-timetable-submit-button";
        $draftTimetableModalId = "draft-timetable-modal-id";
        $draftTimetableModalMessageId = "draft-timetable-modal-message-id";
        $draftTimetableModalTableId = "draft-timetable-modal-table-id";
        $draftTimetableModalButtonMessageId = "draft-timetable-modal-button-message-id";
        $attendanceListDownloadHandlerButtonId = "attendance-list-download-handler-button-id";
        $goodJobClass = "good-job";

        $namePrefixTimetableListAttendanceListCheckinAtId = "timetable-list-attendance-list-checkin-at-id";
        $namePrefixTimetableListAttendanceListCheckoutAtId = "timetable-list-attendance-list-checkout-at-id";
        $namePrefixTimetableListTotalBreakTimeMinuteId = "timetable-list-total-break-time-minute-id";
        $namePrefixTimetableListTotalWorkingTimeMinuteId = "timetable-list-total-working-time-minute-id";
        $namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId
             = "draft-timetable-list-stamp-correction-request-list-checkin-checkout-id";

        $namePrefixDraftTimetableCheckinAt = TimeFieldKind::fieldName(TimeFieldKind::CHECKIN_AT);
        $namePrefixDraftTimetableCheckoutAt = TimeFieldKind::fieldName(TimeFieldKind::CHECKOUT_AT);
        $namePrefixDraftTimetableBreakTimeStartAt = TimeFieldKind::fieldName(TimeFieldKind::BREAK_TIME_START_AT);
        $namePrefixDraftTimetableBreakTimeEndAt = TimeFieldKind::fieldName(TimeFieldKind::BREAK_TIME_END_AT);
        $namePrefixDraftTimetableDescription = TimeFieldKind::fieldName(TimeFieldKind::DESCRIPTION);
        $namePrefixDraftTimetableCheckinCheckout = TimeFieldPairKind::fieldName(TimeFieldPairKind::CHECKIN_CHECKOUT);
        $namePrefixDraftTimetableBreakTimeStartBreakTimeEnd = TimeFieldPairKind::fieldName(TimeFieldPairKind::BREAK_TIME_START_BREAK_TIME_END);

        $timeParseErrorStatusNoTimeFormatMessage = TimeParseErrorStatus::message(TimeParseErrorStatus::NO_TIME_FORMAT);

        $timeValueFieldClass = "time-value-field";
        $descriptionFieldClass = "description-field";
        $disabledFieldClass = "disabled-field";


        $dummyTimetableListsProperties = self::getDummyTimetableListsProperties(
            $timetableListCarbonDayKind,
            $selectedYear,
            $selectedMonth,
            $selectedDay,
            $stampCorrectionRequestListIsAdmitted,
            $dummyTimetableRestrictedUser,
            $isTimetableListUserDefined,
            $isDraftTimetableListUserDefined,
            $redirectTimetableListType,
            $newId,
            $carbonNow,
        );

        $currentTimetableList = $dummyTimetableListsProperties["currentTimetableList"];
        $currentDraftTimetableList = $dummyTimetableListsProperties["currentDraftTimetableList"];

        $currentTimetableListTimetableId = $dummyTimetableListsProperties["currentTimetableListTimetableId"];
        $currentDraftTimetableListDraftTimetableId = $dummyTimetableListsProperties["currentDraftTimetableListDraftTimetableId"];

        $currentTimetableListTimetableUserId =  $dummyTimetableListsProperties["currentTimetableListTimetableUserId"];
        $currentDraftTimetableListDraftTimetableUserId =  $dummyTimetableListsProperties["currentDraftTimetableListDraftTimetableUserId"];

        $breakTimeLists = $dummyTimetableListsProperties["breakTimeLists"];
        $draftBreakTimeLists = $dummyTimetableListsProperties["draftBreakTimeLists"];
        $timetableLists = $dummyTimetableListsProperties["timetableLists"];
        $draftTimetableLists = $dummyTimetableListsProperties["draftTimetableLists"];
        $currentDummyTimetableListReferencedAt = $dummyTimetableListsProperties["currentDummyTimetableListReferencedAt"];

        $postedUserId = null;
        if($postedUserStatus === PostedUserStatus::AUTH_USER){
            $postedUserId = $authUserId;
        }else if($postedUserStatus === PostedUserStatus::CURRENT_TIMETABLE_LIST_TIMETABLE_USER){
            $postedUserId = $currentTimetableListTimetableUserId;
        }else if($postedUserStatus === PostedUserStatus::CURRENT_DRAFT_TIMETABLE_LIST_DRAFT_TIMETABLE_USER){
            $postedUserId = $currentDraftTimetableListDraftTimetableUserId;
        }else if($postedUserStatus === PostedUserStatus::ADMIN_ATTENDANCE_STAFF_ID_USER){
            $postedUserId = $adminAttendanceStaffIdUserId;
        }else if($postedUserStatus === PostedUserStatus::NEW_ID_USER){
            $postedUserId = $newId;
        }//$postedUserStatus

        $postedUser = User::find($postedUserId);

        $routeAdminAttendanceStaffId = null;
        if($postedUserId){
            $routeAdminAttendanceStaffId = route("admin.attendanceStaff.id",$postedUserId);
        }//$postedUserId

        $routeLogin = route("login");
        $routeUpdateDummyTimetableList = route('updateDummyTimetableList');
        $routeCheckin = route("checkin");
        $routeBreakTimeStart = route("breakTimeStart");
        $routeDraftTimetableModal = route("draftTimetableModal");
        $routeDraftTimetableSubmit = route("draftTimetableSubmit");
        $routeDraftTimetableUpdate = route("draftTimetableUpdate");
        $routeDraftTimetableReplace = route("draftTimetableReplace");

        $routeIndex = route("index");
        $routeAttendanceList = route("attendanceList");
        $routeStampCorrectionRequestList = route("stampCorrectionRequestList");
        $routeAdminAttendanceList = route("admin.attendanceList");

        $timetableController = new TimetableController();
        $environment = $timetableController->environment($postedUserId);
        $currentEnvironmentStatusTag = $environment["currentEnvironmentStatusTag"];
        $currentCheckinButtonTag = $environment["currentCheckinButtonTag"];
        $currentBreakTimeStartButtonTag = $environment["currentBreakTimeStartButtonTag"];
        $isCheckinButtonVisible = $environment["isCheckinButtonVisible"];
        $isBreakTimeStartButtonVisible = $environment["isBreakTimeStartButtonVisible"];
        $currentGoodJobTag = $environment["currentGoodJobTag"];
        $isGoodJobVisible = $environment["isGoodJobVisible"];

        $draftTimetableModalButtonProperties = DraftTimetableModalButtonService::getDraftTimetableModalButtonProperties(
            $showFunctionKind,
            $currentDraftTimetableListDraftTimetableId,
        );

        $isDraftTimetableModalButtonVisible = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonVisible"];
        $isDraftTimetableModalButtonDisabled = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonDisabled"];
        $currentDraftTimetableModalButtonTag = $draftTimetableModalButtonProperties["currentDraftTimetableModalButtonTag"];
        $isDraftTimetableModalButtonMessageVisible = $draftTimetableModalButtonProperties["isDraftTimetableModalButtonMessageVisible"];
        $currentDraftTimetableModalButtonMessageTag = $draftTimetableModalButtonProperties["currentDraftTimetableModalButtonMessageTag"];
        $isDisabledField = $draftTimetableModalButtonProperties["isDisabledField"];


        return view($returnedBladeFile,compact(
            "checkinButtonId",
            "breakTimeStartButtonId",
            "draftTimetableModalButtonId",
            "draftTimetableSubmitButtonId",
            "draftTimetableCloseButtonId",
            "draftTimetableModalId",
            "currentYearMonthDayWeekdayId",
            "currentHourMinuteId",
            "currentAttendanceListCheckinAtId",
            "currentAttendanceListCheckoutAtId",
            "currentTotalBreakTimeMinuteId",
            "currentTotalWorkingTimeMinuteId",
            "draftTimetableModalMessageId",
            "draftTimetableModalTableId",
            "draftTimetableModalButtonMessageId",
            "attendanceListDownloadHandlerButtonId",
            "environmentStatusId",
            "namePrefixTimetableListAttendanceListCheckinAtId",
            "namePrefixTimetableListAttendanceListCheckoutAtId",
            "namePrefixTimetableListTotalBreakTimeMinuteId",
            "namePrefixTimetableListTotalWorkingTimeMinuteId",
            "namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId",
            "invisibleAppendingClass",
            "checkinButtonClass",
            "breakTimeStartButtonClass",
            'draftTimetableModalButtonClass',
            'draftTimetableModalButtonMessageClass',
            'draftTimetableSubmitButtonClass',
            'timeParseErrorStatusNoTimeFormatMessage',
            "timeValueFieldClass",
            "descriptionFieldClass",
            "disabledFieldClass",
            "routeLogin",
            "routeUpdateDummyTimetableList",
            "routeCheckin",
            "routeBreakTimeStart",
            'routeDraftTimetableModal',
            'routeDraftTimetableSubmit',
            'routeDraftTimetableUpdate',
            'routeDraftTimetableReplace',
            'routeIndex',
            'routeAttendanceList',
            'routeStampCorrectionRequestList',
            'routeAdminAttendanceList',
            'routeAdminAttendanceStaffId',
            'dummyTimetableRestrictedUserId',

            'namePrefixDraftTimetableCheckinAt',
            'namePrefixDraftTimetableCheckoutAt',
            'namePrefixDraftTimetableBreakTimeStartAt',
            'namePrefixDraftTimetableBreakTimeEndAt',
            'namePrefixDraftTimetableDescription',
            'namePrefixDraftTimetableCheckinCheckout',
            'namePrefixDraftTimetableBreakTimeStartBreakTimeEnd',

            'authUser',
            'postedUser',
            'newId',
            'currentEnvironmentStatusTag',
            'currentCheckinButtonTag',
            'currentBreakTimeStartButtonTag',
            'isCheckinButtonVisible',
            'isBreakTimeStartButtonVisible',
            'currentGoodJobTag',
            'isGoodJobVisible',
            'goodJobClass',
            'redirectTimetableListType',
            'showFunctionKinds',
            'attendanceDetailBladeShowFunctionKind',
            'showFunctionKind',
            'stampCorrectionRequestListIsAdmitted',
            'isMultipleFunctionHeader',
            'bladeUserRole',
            'timetableListCarbonDayKind',
            'selectedYear',
            'selectedMonth',
            'selectedDay',
            'currentDummyTimetableListReferencedAt',
            'currentTimetableList',
            'currentDraftTimetableList',
            'currentTimetableListTimetableId',
            'currentDraftTimetableListDraftTimetableId',
            'breakTimeLists',
            'draftBreakTimeLists',
            'timetableLists',
            'draftTimetableLists',
            'isDraftTimetableModalButtonVisible',
            'isDraftTimetableModalButtonDisabled',
            'currentDraftTimetableModalButtonTag',
            'isDraftTimetableModalButtonMessageVisible',
            'currentDraftTimetableModalButtonMessageTag',
            'isDisabledField',
            'date',
            'previousMonth',
            'nextMonth',
            'previousDay',
            'nextDay',
            'startOfMonth',
            'endOfMonth',
        ));
    }

     public function userLogin(Request $request){
        $id =  null;
        $originalShowFunctionKind = OriginalShowFunctionKind::USER_LOGIN;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function adminLogin(Request $request){
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ADMIN_LOGIN;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function register(Request $request){
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::REGISTER;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function showEmailVerification(Request $request)
    {
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::SHOW_EMAIL_VERIFICATION;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function index(Request $request)
    {
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ATTENDANCE;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function attendanceList(Request $request)
    {
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ATTENDANCE_LIST;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function attendanceDetailId(Request $request,$stringId = null){
        $id = $stringId ? (int)$stringId : null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ATTENDANCE_DETAIL_ID;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function stampCorrectionRequestList(Request $request){
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::STAMP_CORRECTION_REQUEST_LIST;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }//stampCorrectionRequestList

    public function adminAttendanceList(Request $request)
    {
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ADMIN_ATTENDANCE_LIST;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function adminAttendanceId(Request $request,$stringId){
        $id = $stringId ? (int)$stringId : null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ADMIN_ATTENDANCE_ID;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function adminStaffList(Request $request){
        $id = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ADMIN_STAFF_LIST;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function adminAttendanceStaffId(Request $request,$stringId){
        $id = $stringId ? (int)$stringId : null;
        $originalShowFunctionKind = OriginalShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

    public function stampCorrectionRequestApproveAttendanceCorrectRequestId(
        Request $request,
        $stringAttendanceCorrectRequestId
    ){
        $id = (int)$stringAttendanceCorrectRequestId;
        $originalShowFunctionKind = OriginalShowFunctionKind::STAMP_CORRECTION_REQUEST_APPROVE_ATTENDANCE_CORRECT_REQUEST_ID;
        return $this->onCreate($request, $originalShowFunctionKind,$id);
    }

}
