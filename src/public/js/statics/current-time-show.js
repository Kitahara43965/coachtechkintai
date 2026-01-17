import { TimeTool } from "./time-tool.js";

export class CurrentTimeShow {
    static currentTimeShow(config) {
        let currentTimeShowGroup = window.currentTimeShowGroup || {};
        let stringCurrentYearMonthDayWeekday = "";
        let stringCurrentHourMinute = "";
        let stringTimeZone = null;
        let dateCurrentTime = null;
        let stringISODateCurrentTime = null;
        let currentYearMonthDayWeekdayId = null;
        let currentHourMinuteId = null;
        let currentYearMonthDayWeekdayIdElement = null;
        let currentHourMinuteIdElement = null;

        if (config) {
            stringTimeZone = config.stringTimeZone;
            dateCurrentTime = config.dateCurrentTime;
            stringISODateCurrentTime = config.stringISODateCurrentTime;
        } //config

        if (dateCurrentTime) {
            if (TimeTool) {
                stringCurrentYearMonthDayWeekday = TimeTool.getStringYearMonthDayWeekday(dateCurrentTime);
                stringCurrentHourMinute = TimeTool.getStringHourMinute(dateCurrentTime);
            } //TimeTool
        } //dateCurrentTime

        if (currentTimeShowGroup) {
            currentYearMonthDayWeekdayId = currentTimeShowGroup.currentYearMonthDayWeekdayId;
            currentHourMinuteId = currentTimeShowGroup.currentHourMinuteId;
        } //

        // DOM要素を取得
        if (currentYearMonthDayWeekdayId) {
            currentYearMonthDayWeekdayIdElement = document.getElementById(currentYearMonthDayWeekdayId);
            if (currentYearMonthDayWeekdayIdElement) {
                currentYearMonthDayWeekdayIdElement.textContent = stringCurrentYearMonthDayWeekday;
            }
        }

        if (currentHourMinuteId) {
            currentHourMinuteIdElement = document.getElementById(currentHourMinuteId);
            if (currentHourMinuteIdElement) {
                currentHourMinuteIdElement.textContent = stringCurrentHourMinute;
            }
        }
    }
} //CurrentTime
