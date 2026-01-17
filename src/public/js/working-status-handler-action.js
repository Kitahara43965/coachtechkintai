import { STRING_POST_TYPE } from "./constants/constants.js";
import { TimeTool } from "./statics/time-tool.js";

export async function workingStatusHandlerAction(stringPostType, config) {
    const workingStatusHandlerActionGroup = window.workingStatusHandlerActionGroup || {};
    const dateCurrentTime = new Date();
    let invisibleAppendingClass = null;
    let checkinButtonClass = null;
    let breakTimeStartButtonClass = null;
    let goodJobClass = null;
    let checkinButton = null;
    let breakTimeStartButton = null;
    let goodJob = null;
    let routeLaravel = null;
    let response = null;
    let data = null;
    let currentEnvironmentStatusTag = null;
    let currentCheckinButtonTag = null;
    let isCheckinButtonVisible = false;
    let currentBreakTimeStartButtonTag = null;
    let isBreakTimeStartButtonVisible = false;
    let currentGoodJobTag = null;
    let isGoodJobVisible = false;
    let routeCheckin = null;
    let routeBreakTimeStart = null;
    let routeLogin = null;
    let routeIndex = null;
    let csrfToken = null;
    let environmentStatusId = null;
    let environmentStatusIdElement = null;
    let isAlert = false;
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
    let formData = null;
    let stringTimeZone = null;
    let stringISODateCurrentTime = null;
    let isViewBack = false;

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        routeIndex = config.routeIndex;
        invisibleAppendingClass = config.invisibleAppendingClass;
        stringTimeZone = config.stringTimeZone;
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

    postedUserId = postedUserArray ? postedUserArray.id : null;
    currentTimetableListTimetableId = currentTimetableListArray ? currentTimetableListArray.timetable_id : null;
    currentDraftTimetableListDraftTimetableId = currentDraftTimetableListArray ? currentDraftTimetableListArray.draft_timetable_id : null;

    if (workingStatusHandlerActionGroup) {
        checkinButtonClass = workingStatusHandlerActionGroup.checkinButtonClass;
        breakTimeStartButtonClass = workingStatusHandlerActionGroup.breakTimeStartButtonClass;
        environmentStatusId = workingStatusHandlerActionGroup.environmentStatusId;
        routeCheckin = workingStatusHandlerActionGroup.routeCheckin;
        routeBreakTimeStart = workingStatusHandlerActionGroup.routeBreakTimeStart;
        goodJobClass = workingStatusHandlerActionGroup.goodJobClass;
    } //workingStatusHandlerActionGroup

    if (environmentStatusId) {
        environmentStatusIdElement = document.getElementById(environmentStatusId);
    } //environmentStatusId

    if (checkinButtonClass) {
        checkinButton = document.querySelector(`.${checkinButtonClass}`);
    } //checkinButtonClass
    if (breakTimeStartButtonClass) {
        breakTimeStartButton = document.querySelector(`.${breakTimeStartButtonClass}`);
    } //breakTimeStartButtonClass

    if (goodJobClass) {
        goodJob = document.querySelector(`.${goodJobClass}`);
    } //goodJobClass

    formData = {
        stringISODateCurrentTime: stringISODateCurrentTime,
        postedUserId: postedUserId,
        timetableListArrays: timetableListArrays,
        draftTimetableListArrays: draftTimetableListArrays,
        currentTimetableListTimetableId: currentTimetableListTimetableId,
        currentDraftTimetableListDraftTimetableId: currentDraftTimetableListDraftTimetableId,
    };

    if (STRING_POST_TYPE) {
        if (stringPostType === STRING_POST_TYPE.CHECKIN) {
            routeLaravel = routeCheckin;
        } else if (stringPostType === STRING_POST_TYPE.BREAK_TIME_START) {
            routeLaravel = routeBreakTimeStart;
        } //stringPostType
    } //stringPostType

    if (routeLaravel) {
        try {
            response = await fetch(routeLaravel, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            if (response.status === 401 || response.status === 419) {
                // 未認証ならログイン画面へ
                window.location.href = routeLogin;
                return;
            }

            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }

            data = await response.json();
            console.log("サーバーからのデータ:", data);
            currentEnvironmentStatusTag = data.currentEnvironmentStatusTag;
            currentCheckinButtonTag = data.currentCheckinButtonTag;
            isCheckinButtonVisible = data.isCheckinButtonVisible;
            currentBreakTimeStartButtonTag = data.currentBreakTimeStartButtonTag;
            isBreakTimeStartButtonVisible = data.isBreakTimeStartButtonVisible;
            isViewBack = data.isViewBack;

            currentGoodJobTag = data.currentGoodJobTag;
            isGoodJobVisible = data.isGoodJobVisible;

            if (environmentStatusIdElement) {
                environmentStatusIdElement.textContent = `${currentEnvironmentStatusTag}`;
            } //environmentStatusIdElement

            if (checkinButton) {
                checkinButton.textContent = currentCheckinButtonTag;
                if (isCheckinButtonVisible === true) {
                    checkinButton.classList.remove(invisibleAppendingClass);
                } else {
                    checkinButton.classList.add(invisibleAppendingClass);
                }
            } //checkinButton

            if (breakTimeStartButton) {
                breakTimeStartButton.textContent = currentBreakTimeStartButtonTag;
                if (isBreakTimeStartButtonVisible === true) {
                    breakTimeStartButton.classList.remove(invisibleAppendingClass);
                } else {
                    breakTimeStartButton.classList.add(invisibleAppendingClass);
                }
            } //checkinButton

            if (goodJob) {
                goodJob.textContent = currentGoodJobTag;
                if (isGoodJobVisible === true) {
                    goodJob.classList.remove(invisibleAppendingClass);
                } else {
                    goodJob.classList.add(invisibleAppendingClass);
                }
            } //checkinButton

            if (isViewBack === true) {
                if (routeIndex) {
                    window.location.href = routeIndex;
                } //routeIndex
            } //isViewBack

            if (data.status === "success") {
                if (isAlert) {
                    alert("urlの処理が正常に終わりました");
                }
            }
        } catch (err) {
            console.error(err);
        }
    } //isRouteLaravelExistence
} //workingStatusHandlerButton

export function workingStatusHandler(buttonId, stringPostType, config) {
    let buttonIdElement = null;
    if (buttonId) {
        buttonIdElement = document.getElementById(buttonId);
    } //buttonId

    if (buttonIdElement) {
        buttonIdElement.addEventListener("click", async () => {
            await workingStatusHandlerAction(stringPostType, config);
        });
    } //buttonIdElement
} //workingStatusHandler
