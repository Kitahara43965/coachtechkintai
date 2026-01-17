@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')

@php
    use App\Constants\TimetableListCarbonDayKind;
    use App\Constants\ShowFunctionKinds\ShowFunctionKind;

    $stringNameRouteCalendar = null;
    if($showFunctionKind === ShowFunctionKind::ATTENDANCE_LIST){
        $stringNameRouteCalendar = 'redirectToAttendanceListCalendarUpdate';
    }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST){
        $stringNameRouteCalendar = 'redirectToAdminAttendanceListCalendarUpdate';
    }else if($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
        $stringNameRouteCalendar = 'redirectToAdminAttendanceStaffCalendarUpdate.id';
    }//$showFunctionKind

    $isUserName = false;
    $isDateLabel = false;
    if($showFunctionKind === ShowFunctionKind::ATTENDANCE_LIST){
        $isUserName = false;
        $isDateLabel = true;
    }elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST){
        $isUserName = true;
        $isDateLabel = false;
    }elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID){
        $isUserName = false;
        $isDateLabel = true;
    }

    $postedUserName = $postedUser ? $postedUser->name : null;


@endphp


<div class="attendance-list-board">

    <div class="list-title-container">
        @if($showFunctionKind === ShowFunctionKind::ATTENDANCE_LIST)
            <h1 class="list-title">勤怠一覧</h1>
        @elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST)
            <h1 class="list-title">{{$selectedYear}}年{{$selectedMonth}}月{{$selectedDay}}日の勤怠</h1>
        @elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID)
            <h1 class="list-title">{{$postedUserName}}さんの勤怠</h1>
        @endif
    </div>

    <div class = "calendar-container">
        <div>
            @if(!empty($stringNameRouteCalendar))
                
                @if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY)
                    <a class = "calendar-container-prev-text" href="{{ route($stringNameRouteCalendar, [
                        'id' => $newId,
                        'redirectYear' => $previousDay->year,
                        'redirectMonth' => $previousDay->month,
                        'redirectDay' => $previousDay->day,
                    ]) }}"><img src="{{ asset('storage/svg/arrow.svg') }}" alt="テストSVG" class="calendar-container-svg-prev-arrow">前日</a>
                @elseif($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH)
                    <a class = "calendar-container-prev-text" href="{{ route($stringNameRouteCalendar, [
                        'id' => $newId,
                        'redirectYear' => $previousMonth->year,
                        'redirectMonth' => $previousMonth->month,
                        'redirectDay' => $selectedDay,
                    ]) }}"><img src="{{ asset('storage/svg/arrow.svg') }}" alt="テストSVG" class="calendar-container-svg-prev-arrow">前月</a>
                @endif
            @endif
        </div>

        <div>
            <div class="calendar-toggle">

                <button id="calendar-toggle-button" class="calendar-button">
                    <div class="calendar-button-content">
                        <img src="{{ asset('storage/svg/calendar.svg') }}" alt="テストSVG" class="calendar-svg">
                        <div class="calendar-note">
                            @if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY)
                                {{ $date->format('Y/m/d') }}
                            @elseif($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH)
                                {{ $date->format('Y/m') }}
                            @endif
                        </div>
                    </div>
                </button>
                

                <table class="calendar" id="calendar-table">
                    <thead>
                        @if(!empty($stringNameRouteCalendar))
                            @if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY)
                                <tr>
                                    <th>日</th><th>月</th><th>火</th><th>水</th>
                                    <th>木</th><th>金</th><th>土</th>
                                </tr>
                            @elseif($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH)
                            @endif
                        @endif
                    </thead>
                    <tbody>
                        @if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY)
                            @php
                                $current = $startOfMonth->copy()->startOfWeek();
                                $end     = $endOfMonth->copy()->endOfWeek();
                            @endphp
                            @while ($current <= $end)
                                <tr>
                                    @for ($day = 1; $day <= 7; $day++)
                                        <td>
                                            @if ($current->month === $date->month)
                                                @if(!empty($stringNameRouteCalendar))
                                                    <a a class = "calendar-text" href="{{ route($stringNameRouteCalendar, [
                                                        'id' => $newId,
                                                        'redirectYear' => $current->year,
                                                        'redirectMonth' => $current->month,
                                                        'redirectDay' => $current->day,
                                                    ]) }}">
                                                        {{ $current->day }}
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        @php 
                                            $current->addDay(); 
                                        @endphp
                                    @endfor
                                </tr>
                            @endwhile
                        @elseif($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH)
                            @php
                                $month = 0;
                            @endphp
                            <tr>
                                <td colspan="4">{{$selectedYear}}年</td>
                            </tr>
                            @for($rowNumber=1;$rowNumber<=3;$rowNumber++)
                                <tr>
                                    @for($columnNumber=1;$columnNumber<=4;$columnNumber++)
                                        @php
                                            $month = $month + 1;
                                        @endphp
                                        <td>
                                            @if(!empty($stringNameRouteCalendar))
                                                <a a class = "calendar-text" href="{{ route($stringNameRouteCalendar, [
                                                    'id' => $newId,
                                                    'redirectYear' => $selectedYear,
                                                    'redirectMonth' => $month,
                                                    'redirectDay' => $selectedDay,
                                                ]) }}">
                                                    {{ $month }}月
                                                </a>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
            
        <div>
            @if(!empty($stringNameRouteCalendar))
                @if($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_DAY)
                    <a class = "calendar-container-next-text" href="{{ route($stringNameRouteCalendar, [
                        'id' => $newId,
                        'redirectYear' => $nextDay->year,
                        'redirectMonth' => $nextDay->month,
                        'redirectDay' => $nextDay->day,
                    ]) }}">翌日<img src="{{ asset('storage/svg/arrow.svg') }}" alt="テストSVG" class="calendar-container-svg-next-arrow"></a>
                @elseif($timetableListCarbonDayKind === TimetableListCarbonDayKind::SELECTED_MONTH)
                    <a class = "calendar-container-next-text" href="{{ route($stringNameRouteCalendar, [
                        'id' => $newId,
                        'redirectYear' => $nextMonth->year,
                        'redirectMonth' => $nextMonth->month,
                        'redirectDay' => $selectedDay,
                    ]) }}">翌月<img src="{{ asset('storage/svg/arrow.svg') }}" alt="テストSVG" class="calendar-container-svg-next-arrow"></a>
                @endif
            @endif
        </div>

    </div>


    <div class="list-table-container">
        <table class="list-table">
            <thead>
                <tr>
                    @if($isUserName === true)
                        <th class="list-table-header">名前</th>
                    @endif
                    @if($isDateLabel === true)
                        <th class="list-table-header">日付</th>
                    @endif
                    <th class="list-table-header">出勤</th>
                    <th class="list-table-header">退勤</th>
                    <th class="list-table-header">休憩</th>
                    <th class="list-table-header">合計</th>
                    <th class="list-table-header">詳細</th>
                </tr>
            </thead>

            <tbody>

                @if($timetableLists)
                    @foreach($timetableLists as $timetableList)
                        <tr> 
                            @if($isUserName === true)
                                <td class="list-table-content"><div>{{$timetableList->string_timetable_user_name}}</div></td>
                            @endif
                            @if($isDateLabel === true)
                                <td class="list-table-content"><div>{{$timetableList->string_referenced_at_year_month_day_weekday}}</div></td>
                            @endif
                            <td class="list-table-content"><div class="list-table-new-line" 
                                id = "{{$namePrefixTimetableListAttendanceListCheckinAtId}}{{$loop->index}}"}}">{{$timetableList->string_attendance_list_checkin_at}}</div></td>
                            <td class="list-table-content"><div class="list-table-new-line" 
                                id="{{$namePrefixTimetableListAttendanceListCheckoutAtId}}{{$loop->index}}">{{$timetableList->string_attendance_list_checkout_at}}</div></td>
                            <td class="list-table-content"><div class="list-table-new-line" 
                                id="{{$namePrefixTimetableListTotalBreakTimeMinuteId}}{{$loop->index}}">{{$timetableList->string_total_break_time_minute}}</div></td>
                            <td class="list-table-content"><div class="list-table-new-line" 
                                id="{{$namePrefixTimetableListTotalWorkingTimeMinuteId}}{{$loop->index}}">{{$timetableList->string_total_working_time_minute}}</div></td>
                            <td class="list-table-content">
                                @if($showFunctionKind === ShowFunctionKind::ATTENDANCE_LIST)
                                    <a href="{{route('redirectToAttendanceDetailViaAttendanceList.id',[
                                        'id'=>$timetableList->id,
                                        'redirectYear' => $timetableList->referenced_at->year,
                                        'redirectMonth' => $timetableList->referenced_at->month,
                                        'redirectDay' => $timetableList->referenced_at->day,
                                    ])}}" class="list-table-detail">
                                        詳細
                                    </a>
                                @elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_LIST)
                                    <a href="{{route('redirectToAdminAttendanceViaAdminAttendanceList.id',[
                                        'id'=>$timetableList->id,
                                        'redirectYear' => $timetableList->referenced_at->year,
                                        'redirectMonth' => $timetableList->referenced_at->month,
                                        'redirectDay' => $timetableList->referenced_at->day,
                                    ])}}" class="list-table-detail">
                                        詳細
                                    </a>
                                @elseif($showFunctionKind === ShowFunctionKind::ADMIN_ATTENDANCE_STAFF_ID)
                                    <a href="{{route('redirectToAdminAttendanceViaAdminAttendanceStaff.id',[
                                        'id'=>$timetableList->id,
                                        'redirectYear' => $timetableList->referenced_at->year,
                                        'redirectMonth' => $timetableList->referenced_at->month,
                                        'redirectDay' => $timetableList->referenced_at->day,
                                    ])}}" class="list-table-detail">
                                        詳細
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class = "attendance-list-download-button-container">
        <button class = "attendance-list-download-button" id="{{$attendanceListDownloadHandlerButtonId}}">CSV出力</button>
     </div>

</div>



<script>
    window.mainGroup = {
        routeLogin: @json($routeLogin),
        checkinButtonId: @json($checkinButtonId),
        invisibleAppendingClass: @json($invisibleAppendingClass),
        breakTimeStartButtonId: @json($breakTimeStartButtonId),
        draftTimetableModalButtonId:@json($draftTimetableModalButtonId),
        draftTimetableSubmitButtonId:@json($draftTimetableSubmitButtonId),
        draftTimetableCloseButtonId:@json($draftTimetableCloseButtonId),
        draftTimetableModalTableId:@json($draftTimetableModalTableId),
        attendanceListDownloadHandlerButtonId:@json($attendanceListDownloadHandlerButtonId),
        showFunctionKinds:@json($showFunctionKinds),
        showFunctionKind:@json($showFunctionKind),
        attendanceDetailBladeShowFunctionKind:@json($attendanceDetailBladeShowFunctionKind),
        postedUserArray:@json($postedUser),
        timetableListArrays:@json($timetableLists),
        draftTimetableListArrays:@json($draftTimetableLists),
        currentTimetableListArray:@json($currentTimetableList),
        currentDraftTimetableListArray:@json($currentDraftTimetableList),
    }

    window.dummyTimetableListCurrentTimeShowGroup = {
        routeUpdateDummyTimetableList:@json($routeUpdateDummyTimetableList),
        namePrefixTimetableListAttendanceListCheckinAtId:@json($namePrefixTimetableListAttendanceListCheckinAtId),
        namePrefixTimetableListAttendanceListCheckoutAtId:@json($namePrefixTimetableListAttendanceListCheckoutAtId),
        namePrefixTimetableListTotalBreakTimeMinuteId:@json($namePrefixTimetableListTotalBreakTimeMinuteId),
        namePrefixTimetableListTotalWorkingTimeMinuteId:@json($namePrefixTimetableListTotalWorkingTimeMinuteId),
        namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId:
            @json($namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId),
    };

    
    document.getElementById('calendar-toggle-button').addEventListener('click', function() {
        var calendarTable = document.getElementById('calendar-table');
        // 既に表示されている場合は非表示、非表示の場合は表示
        calendarTable.classList.toggle('show');
    });
</script>

<script type="module" src="{{ asset('js/main.js') }}"></script>

@endsection