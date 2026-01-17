import { STRING_POST_TYPE } from "./constants/constants.js";
import { DraftTimetableModal } from "./statics/draft-timetable-modal.js";
import { ErrorMessage } from "./statics/error-message.js";

export async function draftTimetableValidation(routeLaravel, formData, stringPostType, config) {
    const undefinedServerErrorStatus = 0;
    const noServerErrorStatus = 1;
    const noDataServerErrorStatus = 2;
    const validationErrorServerErrorStatus = 3;
    let serverErrorStatus = undefinedServerErrorStatus;
    let stringTimeZone = null;
    let dateNow = null;
    let stringISODateNow = null;
    let response = null;
    let data = null;
    let isServerDataGotten = false;
    let isValidationError = false;
    let routeReturn = null;
    let hasTimeParseErrorStatusNoTimeFormatMessage = false;
    let csrfToken = null;
    let routeLogin = null;
    let routeAttendanceList = null;
    let routeDraftTimetableModalHandlerActionSuccess = null;
    let timeParseErrorStatusNoTimeFormatMessage = null;
    let invisibleAppendingClass = null;
    let showDraftTimetableModalConfig = null;
    let draftTimetableSubmitButtonId = null;
    let draftTimetableCloseButtonId = null;
    let draftTimetableModalTableId = null;
    let draftTimetableModalButtonClass = null;
    let draftTimetableModalButtonMessageClass = null;
    let draftTimetableSubmitButtonClass = null;
    let draftTimetableModalButtonId = null;
    let draftTimetableModalButton = null;
    let draftTimetableModalButtonMessageId = null;
    let draftTimetableModalButtonMessage = null;
    let draftTimetableModalId = null;
    let draftTimetableModalMessageId = null;
    let timeValueFieldClass = null;
    let descriptionFieldClass = null;
    let disabledFieldClass = null;
    let fetchedStatus = null;
    let errors = null;
    let overlappedTimetables = null;
    let overlappedDraftTimetables = null;
    let draftTimetableDTO = null;
    let isDraftTimetableModalButtonVisible = false;
    let isDraftTimetableModalButtonDisabled = false;
    let currentDraftTimetableModalButtonTag = null;
    let isDraftTimetableModalButtonMessageVisible = false;
    let currentDraftTimetableModalButtonMessageTag = null;
    let isDisabledField = false;
    let draftTimetableSubmitButton = null;
    let hasErrorMessageChange = false;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    let attendanceDetailBladeShowFunctionKind = 0;
    let postedUserArray = null;
    let timetableListArrays = null;
    let draftTimetableListArrays = null;
    let currentTimetableListArray = null;
    let currentDraftTimetableListArray = null;
    let timeValueFields = null;
    let descriptionField = null;

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        invisibleAppendingClass = config.invisibleAppendingClass;
        routeAttendanceList = config.routeAttendanceList;
        routeDraftTimetableModalHandlerActionSuccess = config.routeDraftTimetableModalHandlerActionSuccess;
        timeParseErrorStatusNoTimeFormatMessage = config.timeParseErrorStatusNoTimeFormatMessage;
        draftTimetableSubmitButtonId = config.draftTimetableSubmitButtonId;
        draftTimetableCloseButtonId = config.draftTimetableCloseButtonId;
        draftTimetableModalTableId = config.draftTimetableModalTableId;
        stringTimeZone = config.stringTimeZone;
        dateNow = config.dateNow;
        stringISODateNow = config.stringISODateNow;
        draftTimetableModalButtonClass = config.draftTimetableModalButtonClass;
        draftTimetableModalButtonMessageClass = config.draftTimetableModalButtonMessageClass;
        draftTimetableSubmitButtonClass = config.draftTimetableSubmitButtonClass;
        draftTimetableModalButtonId = config.draftTimetableModalButtonId;
        draftTimetableModalButtonMessageId = config.draftTimetableModalButtonMessageId;
        draftTimetableModalId = config.draftTimetableModalId;
        draftTimetableModalMessageId = config.draftTimetableModalMessageId;
        timeValueFieldClass = config.timeValueFieldClass;
        descriptionFieldClass = config.descriptionFieldClass;
        disabledFieldClass = config.disabledFieldClass;
        showFunctionKinds = config.showFunctionKinds;
        showFunctionKind = config.showFunctionKind;
        attendanceDetailBladeShowFunctionKind = config.attendanceDetailBladeShowFunctionKind;
        postedUserArray = config.postedUserArray;
        timetableListArrays = config.timetableListArrays;
        draftTimetableListArrays = config.draftTimetableListArrays;
        currentTimetableListArray = config.currentTimetableListArray;
        currentDraftTimetableListArray = config.currentDraftTimetableListArray;
    } //config

    if (draftTimetableModalButtonId) {
        draftTimetableModalButton = document.getElementById(draftTimetableModalButtonId);
    } //draftTimetableModalButtonId
    if (draftTimetableModalButtonMessageId) {
        draftTimetableModalButtonMessage = document.getElementById(draftTimetableModalButtonMessageId);
    } //draftTimetableModalButtonMessageId

    if (timeValueFieldClass) {
        timeValueFields = document.querySelectorAll("." + timeValueFieldClass);
    } //timeValueFieldClass
    if (descriptionFieldClass) {
        descriptionField = document.querySelector("." + descriptionFieldClass);
    } //descriptionFieldClass

    if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_MODAL) {
        hasErrorMessageChange = true;
    } else if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_SUBMIT) {
        hasErrorMessageChange = true;
    } else if (stringPostType === STRING_POST_TYPE.DRAFT_TIMETABLE_UPDATE) {
        hasErrorMessageChange = false;
    } //stringPostType

    if (routeLaravel) {
        try {
            if (hasErrorMessageChange === true) {
                document.querySelectorAll(".error-message").forEach((span) => {
                    span.textContent = "";
                });
            } //hasErrorMessageChange

            response = await fetch(routeLaravel, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(formData),
            });
            isServerDataGotten = false;
            if (response.status === 401) {
                isServerDataGotten = false;
            } else if (response.status === 404) {
                isServerDataGotten = false;
            } else if (response.status === 419) {
                isServerDataGotten = false;
            } else {
                isServerDataGotten = true;
            }

            if (isServerDataGotten === true) {
                data = await response.json();
                overlappedTimetables = data.overlappedTimetables;
                overlappedDraftTimetables = data.overlappedDraftTimetables;
                fetchedStatus = data.fetchedStatus;
                errors = data.errors;
                draftTimetableDTO = data.draftTimetableDTO;
                isDraftTimetableModalButtonVisible = data.isDraftTimetableModalButtonVisible;
                isDraftTimetableModalButtonDisabled = data.isDraftTimetableModalButtonDisabled;
                currentDraftTimetableModalButtonTag = data.currentDraftTimetableModalButtonTag;
                isDraftTimetableModalButtonMessageVisible = data.isDraftTimetableModalButtonMessageVisible;
                currentDraftTimetableModalButtonMessageTag = data.currentDraftTimetableModalButtonMessageTag;
                isDisabledField = data.isDisabledField;
                console.log("サーバーからのデータ:", data);
            } //isServerDataGotten

            if (draftTimetableModalButton) {
                draftTimetableModalButton.textContent = currentDraftTimetableModalButtonTag;
                if (isDraftTimetableModalButtonVisible === true) {
                    draftTimetableModalButton.classList.remove(invisibleAppendingClass);
                } else {
                    draftTimetableModalButton.classList.add(invisibleAppendingClass);
                }
                if (isDraftTimetableModalButtonDisabled === true) {
                    draftTimetableModalButton.disabled = true;
                    draftTimetableModalButton.style.backgroundColor = "gray";
                } else {
                    draftTimetableModalButton.disabled = false;
                    draftTimetableModalButton.style.backgroundColor = "black";
                }
            } //checkinButton

            if (draftTimetableModalButtonMessage) {
                draftTimetableModalButtonMessage.textContent = currentDraftTimetableModalButtonMessageTag;
                if (isDraftTimetableModalButtonMessageVisible === true) {
                    draftTimetableModalButtonMessage.classList.remove(invisibleAppendingClass);
                } else {
                    draftTimetableModalButtonMessage.classList.add(invisibleAppendingClass);
                }
            } //checkinButton

            if (timeValueFields.length >= 1) {
                timeValueFields.forEach(function (timeValueField) {
                    if (timeValueField) {
                        if (isDisabledField === true) {
                            timeValueField.classList.add(disabledFieldClass);
                            timeValueField.setAttribute("readonly", true);
                        } else {
                            timeValueField.classList.remove(disabledFieldClass);
                            timeValueField.removeAttribute("readonly");
                        } //isDisabledField
                    } //timeValueField
                });
            } //timeValueFields.length

            if (descriptionField) {
                if (isDisabledField === true) {
                    descriptionField.classList.add(disabledFieldClass);
                    descriptionField.setAttribute("readonly", true);
                } else {
                    descriptionField.classList.remove(disabledFieldClass);
                    descriptionField.removeAttribute("readonly");
                } //isDisabledField
            } //descriptionField

            isValidationError = false;
            if (isServerDataGotten === true) {
                if (response.status === 422) {
                    isValidationError = true;
                } //response.status
            } //isServerDataGotten
            if (isValidationError === true) {
                if (ErrorMessage) {
                    if (hasErrorMessageChange === true) {
                        if (typeof ErrorMessage.displayErrorMessages === "function") {
                            ErrorMessage.displayErrorMessages(errors);
                        } //displayErrorMessages
                    } //hasErrorMessageChange

                    if (typeof ErrorMessage.hasErrorMessage === "function") {
                        hasTimeParseErrorStatusNoTimeFormatMessage = ErrorMessage.hasErrorMessage(
                            errors,
                            timeParseErrorStatusNoTimeFormatMessage
                        );
                    } //hasErrorMessage
                } //ErrorMessage
            } //isValidationError

            serverErrorStatus = undefinedServerErrorStatus;
            if (isServerDataGotten === false) {
                serverErrorStatus = noDataServerErrorStatus;
            } else if (isValidationError === true) {
                serverErrorStatus = validationErrorServerErrorStatus;
            } else {
                serverErrorStatus = noServerErrorStatus;
            }

            showDraftTimetableModalConfig = {
                draftTimetableSubmitButtonId: draftTimetableSubmitButtonId,
                draftTimetableModalTableId: draftTimetableModalTableId,
                draftTimetableCloseButtonId: draftTimetableCloseButtonId,
                undefinedServerErrorStatus: undefinedServerErrorStatus,
                noServerErrorStatus: noServerErrorStatus,
                noDataServerErrorStatus: noDataServerErrorStatus,
                validationErrorServerErrorStatus: validationErrorServerErrorStatus,
                draftTimetableModalId: draftTimetableModalId,
                draftTimetableModalMessageId: draftTimetableModalMessageId,
            };

            if (DraftTimetableModal && typeof DraftTimetableModal.showDraftTimetableModal === "function") {
                DraftTimetableModal.showDraftTimetableModal(
                    stringPostType,
                    hasErrorMessageChange,
                    fetchedStatus,
                    serverErrorStatus,
                    hasTimeParseErrorStatusNoTimeFormatMessage,
                    draftTimetableDTO,
                    overlappedTimetables,
                    overlappedDraftTimetables,
                    stringTimeZone,
                    dateNow,
                    showDraftTimetableModalConfig
                );
            } //DraftTimetableModal.showDraftTimetableModal

            routeReturn = null;
            if (serverErrorStatus === noServerErrorStatus) {
                routeReturn = routeDraftTimetableModalHandlerActionSuccess;
            } else if (serverErrorStatus === noDataServerErrorStatus) {
                if (response.status === 401) {
                    routeReturn = routeLogin;
                } else if (response.status === 404) {
                    routeReturn = routeAttendanceList;
                } else if (response.status === 419) {
                    routeReturn = routeLogin;
                } //response.status
            } else if (serverErrorStatus === validationErrorServerErrorStatus) {
                routeReturn = null;
            } //serverErrorStatus

            if (routeReturn) {
                window.location.href = routeReturn;
            } //routeReturn
        } catch (err) {
            console.error(err);
        }
    } //routeLaravel
}
