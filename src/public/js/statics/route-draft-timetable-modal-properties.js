export class RouteDraftTimetableModalProperties {
    static getRouteDraftTimetableModalProperties(
        stringPostType,
        attendanceDetailBladeShowFunctionKind,
        STRING_POST_TYPE,
        showFunctionKinds,
        routeAttendanceList,
        routeStampCorrectionRequestList,
        routeAdminAttendanceList,
        routeAdminAttendanceStaffId,
        routeDraftTimetableModal,
        routeDraftTimetableSubmit,
        routeDraftTimetableUpdate,
        routeDraftTimetableReplace
    ) {
        let routeLaravel = null;
        let routeDraftTimetableModalHandlerActionSuccess = null;
        let routeDraftTimetableModalProperties = null;

        if (STRING_POST_TYPE && typeof showFunctionKinds === "object") {
            if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_MODAL) {
                routeLaravel = routeDraftTimetableModal;
                routeDraftTimetableModalHandlerActionSuccess = null;
            } else if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_SUBMIT) {
                if (attendanceDetailBladeShowFunctionKind === showFunctionKinds.ATTENDANCE_LIST) {
                    routeLaravel = routeDraftTimetableSubmit;
                    routeDraftTimetableModalHandlerActionSuccess = routeAttendanceList;
                } else if (attendanceDetailBladeShowFunctionKind === showFunctionKinds.STAMP_CORRECTION_REQUEST_LIST_FOR_USER) {
                    routeLaravel = routeDraftTimetableSubmit;
                    routeDraftTimetableModalHandlerActionSuccess = routeStampCorrectionRequestList;
                } else if (attendanceDetailBladeShowFunctionKind === showFunctionKinds.ADMIN_ATTENDANCE_LIST) {
                    routeLaravel = routeDraftTimetableReplace;
                    routeDraftTimetableModalHandlerActionSuccess = routeAdminAttendanceList;
                } else if (attendanceDetailBladeShowFunctionKind === showFunctionKinds.ADMIN_ATTENDANCE_STAFF_ID) {
                    routeLaravel = routeDraftTimetableReplace;
                    routeDraftTimetableModalHandlerActionSuccess = routeAdminAttendanceStaffId;
                } else if (attendanceDetailBladeShowFunctionKind === showFunctionKinds.STAMP_CORRECTION_REQUEST_LIST_FOR_ADMIN) {
                    routeLaravel = routeDraftTimetableReplace;
                    routeDraftTimetableModalHandlerActionSuccess = null;
                } else {
                    routeLaravel = null;
                    routeDraftTimetableModalHandlerActionSuccess = null;
                } //attendanceDetailBladeShowFunctionKind
            } else if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_UPDATE) {
                routeLaravel = routeDraftTimetableUpdate;
                routeDraftTimetableModalHandlerActionSuccess = null;
            } //stringPostType
        } //stringPostType

        routeDraftTimetableModalProperties = {
            routeLaravel: routeLaravel,
            routeDraftTimetableModalHandlerActionSuccess: routeDraftTimetableModalHandlerActionSuccess,
        };

        return routeDraftTimetableModalProperties;
    }
}
