import { AttendanceListDownloadHandlerAction } from "./statics/attendance-list-download-handler-action.js";

export function attendanceListDownloadHandler(buttonId, config) {
    let buttonIdElement = null;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    if (buttonId) {
        buttonIdElement = document.getElementById(buttonId);
    } //buttonId

    if (config) {
        showFunctionKind = config.showFunctionKind;
        showFunctionKinds = config.showFunctionKinds;
    } //config

    if (buttonIdElement) {
        if (showFunctionKinds) {
            if (showFunctionKind === showFunctionKinds.ADMIN_ATTENDANCE_STAFF_ID) {
                buttonIdElement.style.display = "block";
            } else {
                buttonIdElement.style.display = "none";
            } //showFunctionKind
        } else {
            buttonIdElement.style.display = "none";
        } //showFunctionKinds
    } //buttonIdElement

    if (buttonIdElement) {
        buttonIdElement.addEventListener("click", async () => {
            if (
                AttendanceListDownloadHandlerAction &&
                typeof AttendanceListDownloadHandlerAction.attendanceListDownloadHandlerAction === "function"
            ) {
                AttendanceListDownloadHandlerAction.attendanceListDownloadHandlerAction(config);
            }
        });
    } //buttonIdElement
} //attendanceListDownloadHandler
