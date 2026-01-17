export async function dummyTimetableListCurrentTimePost(routeLaravel, formData, config) {
    let csrfToken = null;
    let routeLogin = null;
    let namePrefixTimetableListAttendanceListCheckinAtId = null;
    let namePrefixTimetableListAttendanceListCheckoutAtId = null;
    let namePrefixTimetableListTotalBreakTimeMinuteId = null;
    let namePrefixTimetableListTotalWorkingTimeMinuteId = null;
    let namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId = null;
    let response = null;
    let data = null;
    let newTimetableListArrays = null;
    let newDraftTimetableListArrays = null;
    let namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutIndex = null;
    let namePrefixTimetableListAttendanceListCheckinAtIndex = null;
    let namePrefixTimetableListAttendanceListCheckoutAtIndex = null;
    let namePrefixTimetableListTotalBreakTimeMinuteIndex = null;
    let namePrefixTimetableListTotalWorkingTimeMinuteIndex = null;
    let showFunctionKinds = null;
    let showFunctionKind = 0;
    let attendanceDetailBladeShowFunctionKind = 0;
    let postedUserArray = null;
    let timetableListArrays = null;
    let draftTimetableListArrays = null;
    let currentTimetableListArray = null;
    let currentDraftTimetableListArray = null;

    if (config) {
        csrfToken = config.csrfToken;
        routeLogin = config.routeLogin;
        showFunctionKinds = config.showFunctionKinds;
        showFunctionKind = config.showFunctionKind;
        attendanceDetailBladeShowFunctionKind = config.attendanceDetailBladeShowFunctionKind;
        postedUserArray = config.postedUserArray;
        timetableListArrays = config.timetableListArrays;
        draftTimetableListArrays = config.draftTimetableListArrays;
        currentTimetableListArray = config.currentTimetableListArray;
        currentDraftTimetableListArray = config.currentDraftTimetableListArray;
        namePrefixTimetableListAttendanceListCheckinAtId = config.namePrefixTimetableListAttendanceListCheckinAtId;
        namePrefixTimetableListAttendanceListCheckoutAtId = config.namePrefixTimetableListAttendanceListCheckoutAtId;
        namePrefixTimetableListTotalBreakTimeMinuteId = config.namePrefixTimetableListTotalBreakTimeMinuteId;
        namePrefixTimetableListTotalWorkingTimeMinuteId = config.namePrefixTimetableListTotalWorkingTimeMinuteId;
        namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId =
            config.namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId;
    } //config

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

            if (response.status === 419) {
                if (routeLogin) {
                    window.location.href = routeLogin;
                }
            }

            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }

            data = await response.json();
            console.log("サーバーからのデータ:", data);
            newTimetableListArrays = data.newTimetableLists;
            newDraftTimetableListArrays = data.newDraftTimetableLists;

            if (newTimetableListArrays) {
                newTimetableListArrays.forEach((newTimetableListArray, index) => {
                    namePrefixTimetableListAttendanceListCheckinAtIndex = document.getElementById(
                        `${namePrefixTimetableListAttendanceListCheckinAtId}${index}`
                    );
                    namePrefixTimetableListAttendanceListCheckoutAtIndex = document.getElementById(
                        `${namePrefixTimetableListAttendanceListCheckoutAtId}${index}`
                    );
                    namePrefixTimetableListTotalBreakTimeMinuteIndex = document.getElementById(
                        `${namePrefixTimetableListTotalBreakTimeMinuteId}${index}`
                    );
                    namePrefixTimetableListTotalWorkingTimeMinuteIndex = document.getElementById(
                        `${namePrefixTimetableListTotalWorkingTimeMinuteId}${index}`
                    );

                    if (newTimetableListArray) {
                        if (namePrefixTimetableListAttendanceListCheckinAtIndex) {
                            namePrefixTimetableListAttendanceListCheckinAtIndex.innerText =
                                newTimetableListArray.string_attendance_list_checkin_at;
                        } //namePrefixTimetableListAttendanceListCheckinAt
                        if (namePrefixTimetableListAttendanceListCheckoutAtIndex) {
                            namePrefixTimetableListAttendanceListCheckoutAtIndex.innerText =
                                newTimetableListArray.string_attendance_list_checkout_at;
                        } //namePrefixTimetableListAttendanceListCheckinAt
                        if (namePrefixTimetableListTotalBreakTimeMinuteIndex) {
                            namePrefixTimetableListTotalBreakTimeMinuteIndex.innerText =
                                newTimetableListArray.string_total_break_time_minute;
                        } //namePrefixTimetableListTotalBreakTimeMinuteIndex
                        if (namePrefixTimetableListTotalWorkingTimeMinuteIndex) {
                            namePrefixTimetableListTotalWorkingTimeMinuteIndex.innerText =
                                newTimetableListArray.string_total_working_time_minute;
                        } //namePrefixTimetableListTotalWorkingTimeMinuteIndex
                    } //newTimetableListArray
                });
            } //newDraftTimetableListArrays

            if (newDraftTimetableListArrays) {
                newDraftTimetableListArrays.forEach((newDraftTimetableListArray, index) => {
                    namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutIndex = document.getElementById(
                        `${namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId}${index}`
                    );
                    if (newDraftTimetableListArray) {
                        if (namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutIndex) {
                            namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutIndex.innerText =
                                newDraftTimetableListArray.string_stamp_correction_request_list_checkin_checkout;
                        } //namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutIndex
                    } //newDraftTimetableListArray
                });
            } //newDraftTimetableListArrays
        } catch (error) {
            console.error("エラー:", error);
        }
    } //routeLaravel
}
