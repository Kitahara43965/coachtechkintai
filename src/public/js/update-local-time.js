import { STRING_POST_TYPE } from "./constants/constants.js";
import { workingStatusHandlerAction } from "./working-status-handler-action.js";
import { TimeTool } from "./statics/time-tool.js";
import { CurrentTimeShow } from "./statics/current-time-show.js";
import { dummyTimetableListCurrentTimeShow } from "./dummy-timetable-list-current-time-show.js";
import { draftTimetableModalHandlerAction } from "./draft-timetable-modal-handler-action.js";

export async function updateLocalTime(config) {
    let csrfToken = null;
    let routeLogin = null;
    let invisibleAppendingClass = null;
    let stringTimeZone = null;
    let currentTimeShowConfig = null;
    const dateCurrentTime = new Date();
    let stringISODateCurrentTime = null;
    let draftTimetableModalButtonId = null;
    let draftTimetableSubmitButtonId = null;
    let draftTimetableCloseButtonId = null;
    let draftTimetableModalTableId = null;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    let attendanceDetailBladeShowFunctionKind = 0;
    let postedUserArray = null;
    let timetableListArrays = null;
    let draftTimetableListArrays = null;
    let currentTimetableListArray = null;
    let currentDraftTimetableListArray = null;
    let draftTimetableModalHandlerActionConfig = null;
    let workingStatusHandlerActionConfig = null;
    let dummyTimetableListCurrentTimeShowConfig = null;

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        invisibleAppendingClass = config.invisibleAppendingClass;
        stringTimeZone = config.stringTimeZone;
        draftTimetableModalButtonId = config.draftTimetableModalButtonId;
        draftTimetableSubmitButtonId = config.draftTimetableSubmitButtonId;
        draftTimetableCloseButtonId = config.draftTimetableCloseButtonId;
        draftTimetableModalTableId = config.draftTimetableModalTableId;
        showFunctionKinds = config.showFunctionKinds;
        showFunctionKind = config.showFunctionKind;
        attendanceDetailBladeShowFunctionKind = config.attendanceDetailBladeShowFunctionKind;
        postedUserArray = config.postedUserArray;
        timetableListArrays = config.timetableListArrays;
        draftTimetableListArrays = config.draftTimetableListArrays;
        currentTimetableListArray = config.currentTimetableListArray;
        currentDraftTimetableListArray = config.currentDraftTimetableListArray;
    } //config

    if (TimeTool && typeof TimeTool.getLocalizedIsoString === "function") {
        stringISODateCurrentTime = TimeTool.getLocalizedIsoString(dateCurrentTime, stringTimeZone);
    }

    currentTimeShowConfig = {
        stringTimeZone: stringTimeZone,
        dateCurrentTime: dateCurrentTime,
        stringISODateCurrentTime: stringISODateCurrentTime,
    };

    if (CurrentTimeShow && typeof CurrentTimeShow.currentTimeShow === "function") {
        CurrentTimeShow.currentTimeShow(currentTimeShowConfig);
    } //currentTimeShow

    workingStatusHandlerActionConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        invisibleAppendingClass: invisibleAppendingClass,
        stringTimeZone: stringTimeZone,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
    };

    if (typeof workingStatusHandlerAction === "function") {
        workingStatusHandlerAction(STRING_POST_TYPE.ATTENDANCE_UPDATE, workingStatusHandlerActionConfig);
    } //workingStatusHandlerAction

    dummyTimetableListCurrentTimeShowConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        stringTimeZone: stringTimeZone,
        dateCurrentTime: dateCurrentTime,
        stringISODateCurrentTime: stringISODateCurrentTime,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
    };

    if (typeof dummyTimetableListCurrentTimeShow === "function") {
        dummyTimetableListCurrentTimeShow(dummyTimetableListCurrentTimeShowConfig);
    } //dummyTimetableListCurrentTimeShow

    draftTimetableModalHandlerActionConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        invisibleAppendingClass: invisibleAppendingClass,
        stringTimeZone: stringTimeZone,
        draftTimetableModalButtonId: draftTimetableModalButtonId,
        draftTimetableSubmitButtonId: draftTimetableSubmitButtonId,
        draftTimetableCloseButtonId: draftTimetableCloseButtonId,
        draftTimetableModalTableId: draftTimetableModalTableId,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
    };

    if (typeof draftTimetableModalHandlerAction === "function") {
        draftTimetableModalHandlerAction(STRING_POST_TYPE.DRAFT_TIMETABLE_UPDATE, draftTimetableModalHandlerActionConfig);
    } //draftTimetableModalHandlerAction
}
