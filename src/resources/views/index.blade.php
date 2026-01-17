@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection


@section('content')

@php
    if($isCheckinButtonVisible){
        $currentCheckinAppendingClass = "";
    }else{//$isCheckinButtonVisible
        $currentCheckinAppendingClass = " ".$invisibleAppendingClass;
    }//$isCheckinButtonVisible
    if($isBreakTimeStartButtonVisible){
        $currentBreakTimeStartAppendingClass = "";
    }else{//$isBreakTimeStartButtonVisible
        $currentBreakTimeStartAppendingClass = " ".$invisibleAppendingClass;
    }//$isBreakTimeStartButtonVisible
    if($isGoodJobVisible){
        $currentGoodJobAppendingClass = "";
    }else{//$isGoodJobVisible
        $currentGoodJobAppendingClass = " ".$invisibleAppendingClass;
    }//$isGoodJobVisible

@endphp

<div class="index-board">
    <div class="index-button-group-container">

        <div id="{{$environmentStatusId}}" class="index-environment-status">{{$currentEnvironmentStatusTag}}</div>
        
        <div class="index-current-year-month-day-weekday">
            <div id="{{$currentYearMonthDayWeekdayId}}"></div>
        </div>
        <div class="index-current-hour-minute">
            <div id="{{$currentHourMinuteId}}"></div>
        </div>

        <div class="index-button-group">
            <button id="{{$checkinButtonId}}" 
                class="{{$checkinButtonClass.$currentCheckinAppendingClass}}">
                    {{$currentCheckinButtonTag}}
            </button>

            <button id="{{$breakTimeStartButtonId}}" 
                class="{{$breakTimeStartButtonClass.$currentBreakTimeStartAppendingClass}}">
                    {{$currentBreakTimeStartButtonTag}}
            </button>
        </div>

        <div class = "{{$goodJobClass.$currentGoodJobAppendingClass}}">
            {{$currentGoodJobTag}}
        </div>

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

    window.currentTimeShowGroup = {
        currentYearMonthDayWeekdayId: @json($currentYearMonthDayWeekdayId),
        currentHourMinuteId: @json($currentHourMinuteId),
    }

    window.workingStatusHandlerActionGroup = {
        routeCheckin: @json($routeCheckin),
        routeIndex:@json($routeIndex),
        routeBreakTimeStart: @json($routeBreakTimeStart),
        environmentStatusId: @json($environmentStatusId),
        checkinButtonClass: @json($checkinButtonClass),
        breakTimeStartButtonClass: @json($breakTimeStartButtonClass),
        goodJobClass:@json($goodJobClass),

    };
</script>

<script type="module" src="{{ asset('js/main.js') }}"></script>

@endsection
