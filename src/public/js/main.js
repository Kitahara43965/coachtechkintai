import { updateLocalTime } from "./update-local-time.js";
import { workingStatusHandler } from "./working-status-handler-action.js";
import { draftTimetableModalHandler } from "./draft-timetable-modal-handler-action.js";
import { STRING_POST_TYPE } from "./constants/constants.js";
import { attendanceListDownloadHandler } from "./attendance-list-download-handler.js";

document.addEventListener("DOMContentLoaded", () => {
    const isAlert = false;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : "";
    const mainGroup = window.mainGroup || {};
    const stringTimeZone = "Asia/Tokyo";
    let routeLogin = null;
    let checkinButtonId = null;
    let invisibleAppendingClass = null;
    let breakTimeStartButtonId = null;
    let draftTimetableModalButtonId = null;
    let draftTimetableSubmitButtonId = null;
    let draftTimetableCloseButtonId = null;
    let draftTimetableModalTableId = null;
    let attendanceListDownloadHandlerButtonId = null;
    let workingStatusHandlerActionConfig = null;
    let updateLocalTimeConfig = null;
    let draftTimetableModalHandlerActionConfig = null;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    let attendanceDetailBladeShowFunctionKind = 0;
    let postedUserArray = null;
    let timetableListArrays = null;
    let draftTimetableListArrays = null;
    let currentTimetableListArray = null;
    let currentDraftTimetableListArray = null;
    let attendanceListDownloadHandlerConfig = null;

    if (mainGroup) {
        routeLogin = mainGroup.routeLogin;
        checkinButtonId = mainGroup.checkinButtonId;
        invisibleAppendingClass = mainGroup.invisibleAppendingClass;
        breakTimeStartButtonId = mainGroup.breakTimeStartButtonId;
        draftTimetableModalButtonId = mainGroup.draftTimetableModalButtonId;
        draftTimetableSubmitButtonId = mainGroup.draftTimetableSubmitButtonId;
        draftTimetableCloseButtonId = mainGroup.draftTimetableCloseButtonId;
        draftTimetableModalTableId = mainGroup.draftTimetableModalTableId;
        attendanceListDownloadHandlerButtonId = mainGroup.attendanceListDownloadHandlerButtonId;
        showFunctionKinds = mainGroup.showFunctionKinds;
        showFunctionKind = mainGroup.showFunctionKind;
        attendanceDetailBladeShowFunctionKind = mainGroup.attendanceDetailBladeShowFunctionKind;
        postedUserArray = mainGroup.postedUserArray;
        timetableListArrays = mainGroup.timetableListArrays;
        draftTimetableListArrays = mainGroup.draftTimetableListArrays;
        currentTimetableListArray = mainGroup.currentTimetableListArray;
        currentDraftTimetableListArray = mainGroup.currentDraftTimetableListArray;
    } //mainGroup

    updateLocalTimeConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
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
        stringTimeZone: stringTimeZone,
    };

    async function loop() {
        if (typeof updateLocalTime === "function") {
            await updateLocalTime(updateLocalTimeConfig);
        }
        // 次のloopを実行するために再度呼び出しを待つ
        setTimeout(() => loop(), 1000);
    }
    loop();

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

    if (typeof workingStatusHandler === "function") {
        workingStatusHandler(checkinButtonId, STRING_POST_TYPE.CHECKIN, workingStatusHandlerActionConfig);
        workingStatusHandler(breakTimeStartButtonId, STRING_POST_TYPE.BREAK_TIME_START, workingStatusHandlerActionConfig);
    } //workingStatusHandler

    draftTimetableModalHandlerActionConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        invisibleAppendingClass: invisibleAppendingClass,
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
        stringTimeZone: stringTimeZone,
    };

    if (typeof draftTimetableModalHandler === "function") {
        draftTimetableModalHandler(
            draftTimetableModalButtonId,
            STRING_POST_TYPE.DRAFT_TIMETABLE_MODAL,
            draftTimetableModalHandlerActionConfig
        );
        draftTimetableModalHandler(
            draftTimetableSubmitButtonId,
            STRING_POST_TYPE.DRAFT_TIMETABLE_SUBMIT,
            draftTimetableModalHandlerActionConfig
        );
    } //draftTimetableModalHandler

    attendanceListDownloadHandlerConfig = {
        stringTimeZone: stringTimeZone,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
        attendanceListDownloadHandlerButtonId: attendanceListDownloadHandlerButtonId,
    };

    if (typeof attendanceListDownloadHandler === "function") {
        attendanceListDownloadHandler(attendanceListDownloadHandlerButtonId, attendanceListDownloadHandlerConfig);
    }
});
