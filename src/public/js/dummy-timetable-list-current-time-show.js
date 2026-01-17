import { dummyTimetableListCurrentTimePost } from "./dummy-timetable-list-current-time-post.js";

export async function dummyTimetableListCurrentTimeShow(config) {
    const dummyTimetableListCurrentTimeShowGroup = window.dummyTimetableListCurrentTimeShowGroup || {};
    let csrfToken = null;
    let routeLogin = null;
    let stringTimeZone = null;
    let dateCurrentTime = null;
    let stringISODateCurrentTime = null;
    let routeLaravel = null;
    let routeUpdateDummyTimetableList = null;
    let formData = null;
    let namePrefixTimetableListAttendanceListCheckinAtId = null;
    let namePrefixTimetableListAttendanceListCheckoutAtId = null;
    let namePrefixTimetableListTotalBreakTimeMinuteId = null;
    let namePrefixTimetableListTotalWorkingTimeMinuteId = null;
    let namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId = null;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    let attendanceDetailBladeShowFunctionKind = 0;
    let postedUserArray = null;
    let postedUserId = null;
    let timetableListArrays = null;
    let draftTimetableListArrays = null;
    let currentTimetableListArray = null;
    let currentDraftTimetableListArray = null;
    let currentTimetableListTimetableId = null;
    let currentDraftTimetableListDraftTimetableId = null;
    let dummyTimetableListCurrentTimePostConfig = null;

    if (dummyTimetableListCurrentTimeShowGroup) {
        routeUpdateDummyTimetableList = dummyTimetableListCurrentTimeShowGroup.routeUpdateDummyTimetableList;
        namePrefixTimetableListAttendanceListCheckinAtId =
            dummyTimetableListCurrentTimeShowGroup.namePrefixTimetableListAttendanceListCheckinAtId;
        namePrefixTimetableListAttendanceListCheckoutAtId =
            dummyTimetableListCurrentTimeShowGroup.namePrefixTimetableListAttendanceListCheckoutAtId;
        namePrefixTimetableListTotalBreakTimeMinuteId =
            dummyTimetableListCurrentTimeShowGroup.namePrefixTimetableListTotalBreakTimeMinuteId;
        namePrefixTimetableListTotalWorkingTimeMinuteId =
            dummyTimetableListCurrentTimeShowGroup.namePrefixTimetableListTotalWorkingTimeMinuteId;
        namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId =
            dummyTimetableListCurrentTimeShowGroup.namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId;
    } //dummyTimetableListCurrentTimeShowGroup

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        stringTimeZone = config.stringTimeZone;
        showFunctionKinds = config.showFunctionKinds;
        showFunctionKind = config.showFunctionKind;
        attendanceDetailBladeShowFunctionKind = config.attendanceDetailBladeShowFunctionKind;
        postedUserArray = config.postedUserArray;
        timetableListArrays = config.timetableListArrays;
        draftTimetableListArrays = config.draftTimetableListArrays;
        currentTimetableListArray = config.currentTimetableListArray;
        currentDraftTimetableListArray = config.currentDraftTimetableListArray;
        dateCurrentTime = config.dateCurrentTime;
        stringISODateCurrentTime = config.stringISODateCurrentTime;
    }

    postedUserId = postedUserArray ? postedUserArray.id : null;
    currentTimetableListTimetableId = currentTimetableListArray ? currentTimetableListArray.timetable_id : null;
    currentDraftTimetableListDraftTimetableId = currentDraftTimetableListArray ? currentDraftTimetableListArray.draft_timetable_id : null;

    formData = {
        postedUserId: config.postedUserId,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListTimetableId: currentTimetableListTimetableId,
        currentDraftTimetableListDraftTimetableId: currentDraftTimetableListDraftTimetableId,
    };

    routeLaravel = routeUpdateDummyTimetableList;

    dummyTimetableListCurrentTimePostConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
        namePrefixTimetableListAttendanceListCheckinAtId: namePrefixTimetableListAttendanceListCheckinAtId,
        namePrefixTimetableListAttendanceListCheckoutAtId: namePrefixTimetableListAttendanceListCheckoutAtId,
        namePrefixTimetableListTotalBreakTimeMinuteId: namePrefixTimetableListTotalBreakTimeMinuteId,
        namePrefixTimetableListTotalWorkingTimeMinuteId: namePrefixTimetableListTotalWorkingTimeMinuteId,
        namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId:
            namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId,
    };

    if (typeof dummyTimetableListCurrentTimePost === "function") {
        dummyTimetableListCurrentTimePost(routeLaravel, formData, dummyTimetableListCurrentTimePostConfig);
    } //typeof dummyTimetableListCurrentTimePost
} //dummyTimetableListCurrentTimeShow
