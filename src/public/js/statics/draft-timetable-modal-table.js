import { TimeTool } from "./time-tool.js";

export class DraftTimetableModalTable {
    static getStringHtmlDraftTimetableList(draftTimetableDTO, timetables, draftTimetables, stringTimeZone, dateNow) {
        const draftTimetableDTOSetTableNumber = 1;
        const timetableSetTableNumber = 2;
        const draftTimetableSetTableNumber = 3;
        const maxSetTableNumber = 3;
        const labelSetTablePhaseKind = 1;
        const dataSetTablePhaseKind = 2;
        const maxSetTablePhaseKind = 2;
        let tableNumber = 0;
        let maxTableNumber = 0;
        let tablePhaseKind = 0;
        let maxTablePhaseKind = 0;
        let loopTime = 0;
        let timetableNumber = 0;
        let maxTimetableNumber = 0;
        let maxLabelTimetableNumber = 0;
        let maxDataTimetableNumber = 0;
        let totalCharNumber = 0;
        let maxCharNumber = 0;
        let charHtmlDraftTimetableLists = null;
        let stringRow = null;
        let timetable = null;
        let draftTimetable = null;
        let stringHtmlDraftTimetableList = null;
        let charNumber = 0;
        let startTimeAt = null;
        let endTimeAt = null;
        let stringLocalizedISOStartTimeAt = null;
        let stringLocalizedISOEndTimeAt = null;
        let stringStartTimeAt = null;
        let stringEndTimeAt = null;
        let stringDateNowNote = null;

        for (loopTime = 1; loopTime <= 2; loopTime++) {
            totalCharNumber = 0;
            stringRow = `<table>`;
            maxCharNumber = 0;
            if (stringRow) {
                maxCharNumber = stringRow.length;
            } //stringRow
            if (loopTime === 2) {
                for (charNumber = 1; charNumber <= maxCharNumber; charNumber++) {
                    charHtmlDraftTimetableLists[totalCharNumber + charNumber - 1] = stringRow[charNumber - 1];
                } //charNumber
            } //loopTime
            totalCharNumber = totalCharNumber + maxCharNumber;
            maxTableNumber = maxSetTableNumber;
            for (tableNumber = 1; tableNumber <= maxTableNumber; tableNumber++) {
                maxTablePhaseKind = maxSetTablePhaseKind;

                maxDataTimetableNumber = 0;
                if (tableNumber === draftTimetableDTOSetTableNumber) {
                    if (draftTimetableDTO) {
                        maxDataTimetableNumber = 1;
                    } //draftTimetableDTO
                } else if (tableNumber === timetableSetTableNumber) {
                    if (timetables) {
                        maxDataTimetableNumber = timetables.length;
                    } //timetables
                } else if (tableNumber === draftTimetableSetTableNumber) {
                    if (timetables) {
                        maxDataTimetableNumber = draftTimetables.length;
                    } //timetables
                } //tableNumber
                maxLabelTimetableNumber = 0;
                if (maxDataTimetableNumber >= 1) {
                    maxLabelTimetableNumber = 1;
                } //maxDataTimetableNumber

                for (tablePhaseKind = 1; tablePhaseKind <= maxTablePhaseKind; tablePhaseKind++) {
                    maxTimetableNumber = 0;
                    if (tableNumber === draftTimetableDTOSetTableNumber) {
                        if (tablePhaseKind === labelSetTablePhaseKind) {
                            maxTimetableNumber = maxLabelTimetableNumber;
                        } else if (tablePhaseKind === dataSetTablePhaseKind) {
                            maxTimetableNumber = maxDataTimetableNumber;
                        } //tablePhaseKind
                    } else if (tableNumber === timetableSetTableNumber) {
                        if (tablePhaseKind === labelSetTablePhaseKind) {
                            maxTimetableNumber = maxLabelTimetableNumber;
                        } else if (tablePhaseKind === dataSetTablePhaseKind) {
                            maxTimetableNumber = maxDataTimetableNumber;
                        } //tablePhaseKind
                    } else if (tableNumber === draftTimetableSetTableNumber) {
                        if (tablePhaseKind === labelSetTablePhaseKind) {
                            maxTimetableNumber = maxLabelTimetableNumber;
                        } else if (tablePhaseKind === dataSetTablePhaseKind) {
                            maxTimetableNumber = maxDataTimetableNumber;
                        } //tablePhaseKind
                    } //tableNumber

                    for (timetableNumber = 1; timetableNumber <= maxTimetableNumber; timetableNumber++) {
                        timetable = null;
                        draftTimetable = null;
                        startTimeAt = null;
                        endTimeAt = null;
                        if (tableNumber === draftTimetableDTOSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                startTimeAt = draftTimetableDTO.checkin_at;
                                endTimeAt = draftTimetableDTO.checkout_at;
                            } //tablePhaseKind
                        } else if (tableNumber === timetableSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                timetable = timetables[timetableNumber - 1];
                                startTimeAt = timetable.checkin_at;
                                endTimeAt = timetable.checkout_at;
                            } //tablePhaseKind
                        } else if (tableNumber === draftTimetableSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                draftTimetable = draftTimetables[timetableNumber - 1];
                                startTimeAt = draftTimetable.checkin_at;
                                endTimeAt = draftTimetable.checkout_at;
                            } //tablePhaseKind
                        } //tableNumber

                        stringLocalizedISOStartTimeAt = null;
                        stringLocalizedISOEndTimeAt = null;
                        stringStartTimeAt = "";
                        stringEndTimeAt = "";
                        stringDateNowNote = "";
                        if (TimeTool && TimeTool.getLocalizedIsoString) {
                            if (startTimeAt) {
                                stringLocalizedISOStartTimeAt = TimeTool.getLocalizedIsoString(new Date(startTimeAt), stringTimeZone);
                                if (endTimeAt) {
                                    stringLocalizedISOEndTimeAt = TimeTool.getLocalizedIsoString(new Date(endTimeAt), stringTimeZone);
                                    stringDateNowNote = "";
                                } else {
                                    stringLocalizedISOEndTimeAt = TimeTool.getLocalizedIsoString(dateNow, stringTimeZone);
                                    stringDateNowNote = "(現在)";
                                }
                                stringStartTimeAt = stringLocalizedISOStartTimeAt.replace(/-/g, "/");
                                stringEndTimeAt = stringLocalizedISOEndTimeAt.replace(/-/g, "/");
                            } //startTimeAt
                        } //TimeTool

                        stringRow = null;
                        if (tableNumber === draftTimetableDTOSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                                stringRow = `<tr"><td style="padding-top: 20px;" colspan='6'>現在申請中の勤務時間(${maxDataTimetableNumber})</td></tr>`;
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                stringRow = `<tr><td>勤務時間</td><td>${timetableNumber}:</td><td>${stringStartTimeAt}</td><td>~</td><td>${stringEndTimeAt}</td><td>${stringDateNowNote}</td></tr>`;
                            } //tablePhaseKind
                        } else if (tableNumber === timetableSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                                stringRow = `<tr style="color: red;"><td style="padding-top: 20px;" colspan='6'>時間の重複した勤務時間(${maxDataTimetableNumber})</td></tr>`;
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                stringRow = `<tr style="color: red;"><td>勤務時間</td><td>${timetableNumber}:</td><td>${stringStartTimeAt}</td><td>~</td><td>${stringEndTimeAt}</td><td>${stringDateNowNote}</td></tr>`;
                            } //tablePhaseKind
                        } else if (tableNumber === draftTimetableSetTableNumber) {
                            if (tablePhaseKind === labelSetTablePhaseKind) {
                                stringRow = `<tr"><td style="padding-top: 20px;" colspan='6'>時間の重複した修正申請中の勤務時間(${maxDataTimetableNumber})</td></tr>`;
                            } else if (tablePhaseKind === dataSetTablePhaseKind) {
                                stringRow = `<tr><td>勤務時間</td><td>${timetableNumber}:</td><td>${stringStartTimeAt}</td><td>~</td><td>${stringEndTimeAt}</td><td>${stringDateNowNote}</td></tr>`;
                            } //tablePhaseKind
                        } //tableNumber
                        maxCharNumber = 0;
                        if (stringRow) {
                            maxCharNumber = stringRow.length;
                        } //stringRow
                        if (loopTime === 2) {
                            for (charNumber = 1; charNumber <= maxCharNumber; charNumber++) {
                                charHtmlDraftTimetableLists[totalCharNumber + charNumber - 1] = stringRow[charNumber - 1];
                            } //charNumber
                        } //loopTime
                        totalCharNumber = totalCharNumber + maxCharNumber;
                    } //timetableNumber
                } //tablePhaseKind
            } //tableNumber
            stringRow = `</table>`;
            maxCharNumber = 0;
            if (stringRow) {
                maxCharNumber = stringRow.length;
            } //stringRow
            if (loopTime === 2) {
                for (charNumber = 1; charNumber <= maxCharNumber; charNumber++) {
                    charHtmlDraftTimetableLists[totalCharNumber + charNumber - 1] = stringRow[charNumber - 1];
                } //charNumber
            } //loopTime
            totalCharNumber = totalCharNumber + maxCharNumber;
            if (loopTime === 1) {
                charHtmlDraftTimetableLists = new Array(totalCharNumber);
            } //loopTime
        } //loopTime
        if (charHtmlDraftTimetableLists) {
            stringHtmlDraftTimetableList = charHtmlDraftTimetableLists.join("");
        } //charHtmlDraftTimetableLists
        return stringHtmlDraftTimetableList;
    } //getModalDraftTimetableList
} //TimeTool
