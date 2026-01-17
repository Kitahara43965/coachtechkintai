import { STRING_POST_TYPE } from "./constants/constants.js";
import { draftTimetableValidation } from "./draft-timetable-validation.js";
import { TimeTool } from "./statics/time-tool.js";
import { RouteDraftTimetableModalProperties } from "./statics/route-draft-timetable-modal-properties.js";

export async function draftTimetableModalHandlerAction(stringPostType, config) {
    const draftTimetableModalHandlerActionGroup = window.draftTimetableModalHandlerActionGroup || {};
    const dateNow = new Date();
    let isAlert = false;
    let formData = null;
    let routeDraftTimetableModal = null;
    let routeDraftTimetableSubmit = null;
    let routeDraftTimetableUpdate = null;
    let routeDraftTimetableReplace = null;
    let routeAttendanceList = null;
    let routeDraftTimetableModalHandlerActionSuccess = null;
    let routeLaravel = null;
    let routeLogin = null;
    let invisibleAppendingClass = null;
    let routeStampCorrectionRequestList = null;
    let routeAdminAttendanceList = null;
    let routeAdminAttendanceStaffId = null;
    let csrfToken = null;
    let stringTimeZone = null;
    let stringISODateNow = null;
    let draftTimetableModalButtonId = null;
    let draftTimetableSubmitButtonId = null;
    let draftTimetableCloseButtonId = null;
    let draftTimetableModalTableId = null;
    let draftTimetableModalButtonClass = null;
    let draftTimetableModalButtonMessageClass = null;
    let draftTimetableSubmitButtonClass = null;
    let namePrefixDraftTimetableCheckinAt = null;
    let querySelectorDraftTimetableCheckinAt = null;
    let valueDraftTimetableCheckinAt = null;
    let namePrefixDraftTimetableCheckoutAt = null;
    let querySelectorDraftTimetableCheckoutAt = null;
    let valueDraftTimetableCheckoutAt = null;
    let namePrefixDraftTimetableBreakTimeStartAt = null;
    let querySelectorDraftTimetableBreakTimeStartAts = null;
    let valueDraftTimetableBreakTimeStartAts = null;
    let namePrefixDraftTimetableBreakTimeEndAt = null;
    let querySelectorDraftTimetableBreakTimeEndAts = null;
    let valueDraftTimetableBreakTimeEndAts = null;
    let namePrefixDraftTimetableDescription = null;
    let querySelectorDraftTimetableDescription = null;
    let valueDraftTimetableDescription = null;
    let timeParseErrorStatusNoTimeFormatMessage = null;
    let draftTimetableModalButtonMessageId = null;
    let draftTimetableModalId = null;
    let draftTimetableModalMessageId = null;
    let draftTimetableValidationConfig = null;
    let currentDummyTimetableListReferencedAt = null;
    let routeDraftTimetableModalProperties = null;
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
    let timeValueFieldClass = null;
    let descriptionFieldClass = null;
    let disabledFieldClass = null;

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        invisibleAppendingClass = config.invisibleAppendingClass;
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
        stringTimeZone = config.stringTimeZone;
    } //config

    postedUserId = postedUserArray ? postedUserArray.id : null;
    currentTimetableListTimetableId = currentTimetableListArray ? currentTimetableListArray.timetable_id : null;
    currentDraftTimetableListDraftTimetableId = currentDraftTimetableListArray ? currentDraftTimetableListArray.draft_timetable_id : null;

    if (TimeTool && typeof TimeTool.getLocalizedIsoString === "function") {
        stringISODateNow = TimeTool.getLocalizedIsoString(dateNow, stringTimeZone);
    }

    if (draftTimetableModalHandlerActionGroup) {
        currentDummyTimetableListReferencedAt = draftTimetableModalHandlerActionGroup.currentDummyTimetableListReferencedAt;
        routeDraftTimetableModal = draftTimetableModalHandlerActionGroup.routeDraftTimetableModal;
        routeDraftTimetableSubmit = draftTimetableModalHandlerActionGroup.routeDraftTimetableSubmit;
        routeDraftTimetableUpdate = draftTimetableModalHandlerActionGroup.routeDraftTimetableUpdate;
        routeDraftTimetableReplace = draftTimetableModalHandlerActionGroup.routeDraftTimetableReplace;
        routeAttendanceList = draftTimetableModalHandlerActionGroup.routeAttendanceList;
        routeStampCorrectionRequestList = draftTimetableModalHandlerActionGroup.routeStampCorrectionRequestList;
        routeAdminAttendanceList = draftTimetableModalHandlerActionGroup.routeAdminAttendanceList;
        routeAdminAttendanceStaffId = draftTimetableModalHandlerActionGroup.routeAdminAttendanceStaffId;
        namePrefixDraftTimetableCheckinAt = draftTimetableModalHandlerActionGroup.namePrefixDraftTimetableCheckinAt;
        namePrefixDraftTimetableCheckoutAt = draftTimetableModalHandlerActionGroup.namePrefixDraftTimetableCheckoutAt;
        namePrefixDraftTimetableBreakTimeStartAt = draftTimetableModalHandlerActionGroup.namePrefixDraftTimetableBreakTimeStartAt;
        namePrefixDraftTimetableBreakTimeEndAt = draftTimetableModalHandlerActionGroup.namePrefixDraftTimetableBreakTimeEndAt;
        namePrefixDraftTimetableDescription = draftTimetableModalHandlerActionGroup.namePrefixDraftTimetableDescription;
        timeParseErrorStatusNoTimeFormatMessage = draftTimetableModalHandlerActionGroup.timeParseErrorStatusNoTimeFormatMessage;
        draftTimetableModalButtonClass = draftTimetableModalHandlerActionGroup.draftTimetableModalButtonClass;
        draftTimetableModalButtonMessageClass = draftTimetableModalHandlerActionGroup.draftTimetableModalButtonMessageClass;
        draftTimetableSubmitButtonClass = draftTimetableModalHandlerActionGroup.draftTimetableSubmitButtonClass;
        draftTimetableModalId = draftTimetableModalHandlerActionGroup.draftTimetableModalId;
        draftTimetableModalButtonMessageId = draftTimetableModalHandlerActionGroup.draftTimetableModalButtonMessageId;
        draftTimetableModalMessageId = draftTimetableModalHandlerActionGroup.draftTimetableModalMessageId;
        timeValueFieldClass = draftTimetableModalHandlerActionGroup.timeValueFieldClass;
        descriptionFieldClass = draftTimetableModalHandlerActionGroup.descriptionFieldClass;
        disabledFieldClass = draftTimetableModalHandlerActionGroup.disabledFieldClass;
    } //draftTimetableModalHandlerActionGroup

    if (namePrefixDraftTimetableCheckinAt) {
        querySelectorDraftTimetableCheckinAt = document.querySelector(`input[name="${namePrefixDraftTimetableCheckinAt}"]`);
        if (querySelectorDraftTimetableCheckinAt) {
            valueDraftTimetableCheckinAt = querySelectorDraftTimetableCheckinAt.value;
        } //querySelectorDraftTimetableCheckinAt
    } //namePrefixDraftTimetableCheckinAt

    if (namePrefixDraftTimetableCheckoutAt) {
        querySelectorDraftTimetableCheckoutAt = document.querySelector(`input[name="${namePrefixDraftTimetableCheckoutAt}"]`);
        if (querySelectorDraftTimetableCheckoutAt) {
            valueDraftTimetableCheckoutAt = querySelectorDraftTimetableCheckoutAt.value;
        } //querySelectorDraftTimetableCheckoutAt
    } //namePrefixDraftTimetableCheckoutAt

    if (namePrefixDraftTimetableBreakTimeStartAt) {
        querySelectorDraftTimetableBreakTimeStartAts = document.querySelectorAll(
            `input[name="${namePrefixDraftTimetableBreakTimeStartAt}[]"]`
        );
        if (querySelectorDraftTimetableBreakTimeStartAts.length > 0) {
            valueDraftTimetableBreakTimeStartAts = Array.from(querySelectorDraftTimetableBreakTimeStartAts).map((input) => input.value);
        } //querySelectorDraftTimetableBreakTimeStartAts
    } //namePrefixDraftTimetableBreakTimeStartAt

    if (namePrefixDraftTimetableBreakTimeEndAt) {
        querySelectorDraftTimetableBreakTimeEndAts = document.querySelectorAll(`input[name="${namePrefixDraftTimetableBreakTimeEndAt}[]"]`);
        if (querySelectorDraftTimetableBreakTimeEndAts.length > 0) {
            valueDraftTimetableBreakTimeEndAts = Array.from(querySelectorDraftTimetableBreakTimeEndAts).map((input) => input.value);
        } //querySelectorDraftTimetableBreakTimeEndAts
    } //namePrefixDraftTimetableBreakTimeEndAt

    if (namePrefixDraftTimetableDescription) {
        querySelectorDraftTimetableDescription = document.querySelector(`textarea[name="${namePrefixDraftTimetableDescription}"]`);
        if (querySelectorDraftTimetableDescription) {
            valueDraftTimetableDescription = querySelectorDraftTimetableDescription.value;
        } //querySelectorDraftTimetableDescription
    } //namePrefixDraftTimetableDescription

    if (RouteDraftTimetableModalProperties && RouteDraftTimetableModalProperties.getRouteDraftTimetableModalProperties) {
        routeDraftTimetableModalProperties = RouteDraftTimetableModalProperties.getRouteDraftTimetableModalProperties(
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
        );
    }

    if (routeDraftTimetableModalProperties && typeof routeDraftTimetableModalProperties === "object") {
        routeDraftTimetableModalHandlerActionSuccess = routeDraftTimetableModalProperties.routeDraftTimetableModalHandlerActionSuccess;
        routeLaravel = routeDraftTimetableModalProperties.routeLaravel;
    }

    formData = {
        [namePrefixDraftTimetableCheckinAt]: valueDraftTimetableCheckinAt,
        [namePrefixDraftTimetableCheckoutAt]: valueDraftTimetableCheckoutAt,
        [namePrefixDraftTimetableBreakTimeStartAt]: valueDraftTimetableBreakTimeStartAts,
        [namePrefixDraftTimetableBreakTimeEndAt]: valueDraftTimetableBreakTimeEndAts,
        [namePrefixDraftTimetableDescription]: valueDraftTimetableDescription,
        stringISODateNow: stringISODateNow,
        showFunctionKind: showFunctionKind,
        postedUserId: postedUserId,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListTimetableId: currentTimetableListTimetableId,
        currentDraftTimetableListDraftTimetableId: currentDraftTimetableListDraftTimetableId,
        currentDummyTimetableListReferencedAt: currentDummyTimetableListReferencedAt,
    };

    draftTimetableValidationConfig = {
        csrfToken: csrfToken,
        routeLogin: routeLogin,
        invisibleAppendingClass: invisibleAppendingClass,
        routeAttendanceList: routeAttendanceList,
        routeDraftTimetableModalHandlerActionSuccess: routeDraftTimetableModalHandlerActionSuccess,
        timeParseErrorStatusNoTimeFormatMessage: timeParseErrorStatusNoTimeFormatMessage,
        draftTimetableSubmitButtonId: draftTimetableSubmitButtonId,
        draftTimetableCloseButtonId: draftTimetableCloseButtonId,
        draftTimetableModalTableId: draftTimetableModalTableId,
        stringTimeZone: stringTimeZone,
        showFunctionKinds: showFunctionKinds,
        showFunctionKind: showFunctionKind,
        attendanceDetailBladeShowFunctionKind: attendanceDetailBladeShowFunctionKind,
        postedUserArray: postedUserArray,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListArray: currentTimetableListArray,
        currentDraftTimetableListArray: currentDraftTimetableListArray,
        dateNow: dateNow,
        stringISODateNow: stringISODateNow,
        draftTimetableModalButtonClass: draftTimetableModalButtonClass,
        draftTimetableModalButtonMessageClass: draftTimetableModalButtonMessageClass,
        draftTimetableSubmitButtonClass: draftTimetableSubmitButtonClass,
        draftTimetableModalButtonId: draftTimetableModalButtonId,
        draftTimetableModalButtonMessageId: draftTimetableModalButtonMessageId,
        draftTimetableModalId: draftTimetableModalId,
        draftTimetableModalMessageId: draftTimetableModalMessageId,
        timeValueFieldClass: timeValueFieldClass,
        descriptionFieldClass: descriptionFieldClass,
        disabledFieldClass: disabledFieldClass,
    };

    await draftTimetableValidation(routeLaravel, formData, stringPostType, draftTimetableValidationConfig);
}

export function draftTimetableModalHandler(buttonId, stringPostType, config) {
    let buttonIdElement = null;
    if (buttonId) {
        buttonIdElement = document.getElementById(buttonId);
    } //buttonId

    if (buttonIdElement) {
        buttonIdElement.addEventListener("click", async () => {
            await draftTimetableModalHandlerAction(stringPostType, config);
        });
    } //buttonIdElement
} //draftTimetableModalHandler
