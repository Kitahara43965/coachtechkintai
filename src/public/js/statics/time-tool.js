export class TimeTool {
    static getStringYear(dateTime) {
        const stringYear = String(dateTime.getFullYear());
        return stringYear;
    }

    static getStringMonth(dateTime) {
        const stringMonth = String(dateTime.getMonth() + 1).padStart(2, "0");
        return stringMonth;
    }

    static getStringDay(dateTime) {
        const stringDay = String(dateTime.getDate()).padStart(2, "0");
        return stringDay;
    }

    static getStringWeekday(dateTime) {
        const stringWeekdays = ["日", "月", "火", "水", "木", "金", "土"];
        const stringWeekday = stringWeekdays[dateTime.getDay()];
        return stringWeekday;
    }

    static getStringHour(dateTime) {
        const stringHour = String(dateTime.getHours()).padStart(2, "0");
        return stringHour;
    }

    static getStringMinute(dateTime) {
        const stringMinute = String(dateTime.getMinutes()).padStart(2, "0");
        return stringMinute;
    }

    static getStringSecond(dateTime) {
        const stringSecond = String(dateTime.getSeconds()).padStart(2, "0");
        return stringSecond;
    }

    static getStringYearMonthDayWeekday(dateTime) {
        const stringYear = this.getStringYear(dateTime);
        const stringMonth = this.getStringMonth(dateTime);
        const stringDay = this.getStringDay(dateTime);
        const stringWeekday = this.getStringWeekday(dateTime);
        const stringYearMonthDayWeekday = `${stringYear}/${stringMonth}/${stringDay} (${stringWeekday})`;
        return stringYearMonthDayWeekday;
    }

    static getStringHourMinute(dateTime) {
        const stringHour = this.getStringHour(dateTime);
        const stringMinute = this.getStringMinute(dateTime);
        const stringHourMinute = `${stringHour}:${stringMinute}`;
        return stringHourMinute;
    }

    static getLocalizedIsoString(date, stringTimeZone) {
        const stringInitialTimeZone = "Europe/London";
        const dateCurrentTime = new Date();
        let localizedDate = null;
        let stringCompensatedTimeZone = null;
        let localizedYear = 0;
        let localizedMonth = 0;
        let localizedDay = 0;
        let localizedHour = 0;
        let localizedMinute = 0;
        let localizedSecond = 0;
        let stringLocalizedTime = null;
        let localizedDateOptions = null;
        let stringLocalizedDateFormatInfo = null;

        if (stringTimeZone) {
            stringCompensatedTimeZone = stringTimeZone;
        } else {
            stringCompensatedTimeZone = stringInitialTimeZone;
        } //stringTimeZone

        if (date) {
            localizedDateOptions = {
                timeZone: stringCompensatedTimeZone,
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: false,
            };

            stringLocalizedDateFormatInfo = new Intl.DateTimeFormat("en-US", localizedDateOptions).format(date);

            localizedDate = new Date(stringLocalizedDateFormatInfo);
        } else {
            localizedDate = dateCurrentTime;
        }

        localizedYear = localizedDate.getFullYear();
        localizedMonth = localizedDate.getMonth() + 1;
        localizedDay = localizedDate.getDate();
        localizedHour = localizedDate.getHours();
        localizedMinute = localizedDate.getMinutes();
        localizedSecond = localizedDate.getSeconds();

        stringLocalizedTime = `${localizedYear}-${String(localizedMonth).padStart(2, "0")}-${String(localizedDay).padStart(
            2,
            "0"
        )} ${String(localizedHour).padStart(2, "0")}:${String(localizedMinute).padStart(2, "0")}:${String(localizedSecond).padStart(
            2,
            "0"
        )}`;

        return stringLocalizedTime;
    }
} //TimeTool
