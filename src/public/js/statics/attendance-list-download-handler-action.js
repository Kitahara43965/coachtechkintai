import { TimeTool } from "./time-tool.js";

export class AttendanceListDownloadHandlerAction {
    static attendanceListDownloadHandlerAction(config) {
        const dateCurrentTime = new Date();
        let stringISODateCurrentTime = null;
        let stringTimeZone = null;
        let showFunctionKinds = null;
        let showFunctionKind = 0;
        let attendanceDetailBladeShowFunctionKind = 0;
        let postedUserArray = null;
        let postedUserName = null;
        let timetableListArrays = null;
        let draftTimetableListArrays = null;
        let currentTimetableListArray = null;
        let currentDraftTimetableListArray = null;
        let attendanceListDownloadHandlerButtonId = null;
        let maxCsvListNumber = 0;
        let csvLists = null;
        let timetableListArray = null;

        if (config) {
            stringTimeZone = config.stringTimeZone;
            showFunctionKinds = config.showFunctionKinds;
            showFunctionKind = config.showFunctionKind;
            attendanceDetailBladeShowFunctionKind = config.attendanceDetailBladeShowFunctionKind;
            postedUserArray = config.postedUserArray;
            timetableListArrays = config.timetableListArrays;
            draftTimetableListArrays = config.draftTimetableListArrays;
            currentTimetableListArray = config.currentTimetableListArray;
            currentDraftTimetableListArray = config.currentDraftTimetableListArray;
            attendanceListDownloadHandlerButtonId = config.attendanceListDownloadHandlerButtonId;
        }

        if (TimeTool && typeof TimeTool.getLocalizedIsoString === "function") {
            stringISODateCurrentTime = TimeTool.getLocalizedIsoString(dateCurrentTime, stringTimeZone);
        }

        if (postedUserArray) {
            postedUserName = postedUserArray.name ? postedUserArray.name : "Undefined User Name";
        }

        maxCsvListNumber = 0;
        if (timetableListArrays) {
            maxCsvListNumber = timetableListArrays.length;
        }

        if (maxCsvListNumber >= 1) {
            csvLists = new Array(maxCsvListNumber);
        }

        // 修正: for ループのインデックス変数を正しく定義
        for (let csvListNumber = 1; csvListNumber <= maxCsvListNumber; csvListNumber++) {
            timetableListArray = timetableListArrays[csvListNumber - 1];
            let csvList = {
                string_referenced_at_year_month_day_weekday: timetableListArray.string_referenced_at_year_month_day_weekday || "",
                string_attendance_list_checkin_at: timetableListArray.string_attendance_list_checkin_at || "",
                string_attendance_list_checkout_at: timetableListArray.string_attendance_list_checkout_at || "",
                string_total_break_time_minute: timetableListArray.string_total_break_time_minute || "",
                string_total_working_time_minute: timetableListArray.string_total_working_time_minute || "",
            };
            csvLists[csvListNumber - 1] = csvList;
        }

        // CSVに変換する関数
        function convertAttendanceListToCSV(csvLists, postedUserName) {
            const postedUserInfo = [postedUserName + "さんの勤怠", "", "", "", ""];
            const header = ["日付", "出勤", "退勤", "休憩", "合計"];
            const rows = csvLists.map((csvList) => [
                csvList.string_referenced_at_year_month_day_weekday,
                csvList.string_attendance_list_checkin_at,
                csvList.string_attendance_list_checkout_at,
                csvList.string_total_break_time_minute,
                csvList.string_total_working_time_minute,
            ]);

            const allData = [postedUserInfo, header, ...rows];
            return allData.map((row) => row.join(",")).join("\n");
        }

        function downloadAttendanceListCsvFile(csvLists, postedUserName, stringISODateCurrentTime) {
            const csvContent = convertAttendanceListToCSV(csvLists, postedUserName);
            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            const filename = "attendance_list_" + postedUserName + "_" + stringISODateCurrentTime + ".csv";

            if (navigator.msSaveBlob) {
                // IE用
                navigator.msSaveBlob(blob, filename);
            } else {
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.click();
                URL.revokeObjectURL(url); // 使用後にURLを解放
            }
        }

        // CSVファイルのダウンロードを開始
        downloadAttendanceListCsvFile(csvLists, postedUserName, stringISODateCurrentTime);
    }
}
