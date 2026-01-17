@extends('layouts.app')

@php
    use App\Constants\TimetableListType;
@endphp

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')

    @php
        $postedUserName = null;
        $maxDummyBreakTimeListNumber = 0;
        $stringReferencedAtLetterYear = null;
        $stringReferencedAtLetterMonthDay = null;
        $stringValueCheckinAt = null;
        $stringValueCheckoutAt = null;
        $stringValueDescription = null;
        $draftTimetableIsAdmitted = false;

        if($postedUser){
            $postedUserName = $postedUser -> name;
        }//$postedUser

        if($redirectTimetableListType === TimetableListType::TIMETABLE_LIST){
            if($breakTimeLists){
                $maxDummyBreakTimeListNumber = count($breakTimeLists);
            }//$breakTimeLists
            if($currentTimetableList){
                $stringReferencedAtLetterYear = $currentTimetableList->string_referenced_at_lettered_year;
                $stringReferencedAtLetterMonthDay = $currentTimetableList->string_referenced_at_lettered_month_day;
                $stringValueCheckinAt = $currentTimetableList->string_attendance_detail_checkin_at;
                $stringValueCheckoutAt = $currentTimetableList->string_attendance_detail_checkout_at;
                $stringValueDescription = $currentTimetableList->string_timetable_description;
            }//$currentTimetableList
        }else if($redirectTimetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
            if($draftBreakTimeLists){
                $maxDummyBreakTimeListNumber = count($draftBreakTimeLists);
            }//$draftBreakTimeLists
            if($currentDraftTimetableList){
                $stringReferencedAtLetterYear = $currentDraftTimetableList->string_referenced_at_lettered_year;
                $stringReferencedAtLetterMonthDay = $currentDraftTimetableList->string_referenced_at_lettered_month_day;
                $stringValueCheckinAt = $currentDraftTimetableList->string_attendance_detail_checkin_at;
                $stringValueCheckoutAt = $currentDraftTimetableList->string_attendance_detail_checkout_at;
                $stringValueDescription = $currentDraftTimetableList->string_draft_timetable_description;
            }//$currentDraftTimetableList
        }//$redirectTimetableListType

        if($isDraftTimetableModalButtonVisible === true){
            $currentDraftTimetableModalButtonAppendingClass = "";
        }else{//$isDraftTimetableModalButtonVisible
            $currentDraftTimetableModalButtonAppendingClass = " ".$invisibleAppendingClass;
        }//$isDraftTimetableModalButtonVisible

        if($isDraftTimetableModalButtonDisabled === true){
            $stringDraftTimetableModalButtonDisabled = " disabled";
        }else{//$isDraftTimetableModalButtonDisabled
            $stringDraftTimetableModalButtonDisabled = "";
        }//$isDraftTimetableModalButtonDisabled


        if($isDraftTimetableModalButtonMessageVisible === true){
            $currentDraftTimetableModalButtonMessageAppendingClass = "";
        }else{//$isDraftTimetableModalButtonMessageVisible
            $currentDraftTimetableModalButtonMessageAppendingClass = " ".$invisibleAppendingClass;
        }//$isDraftTimetableModalButtonMessageVisible

        if($isDisabledField === true){
            $currentFieldAppendingClass = " ".$disabledFieldClass;
            $stringFieldReadOnly = " readonly";
        }else{//$isDisabledField
            $currentFieldAppendingClass = "";
            $stringFieldReadOnly = "";
        }//$isDisabledField
        
    @endphp


    <div class="attendance-detail-board">
        <div class="list-title-container">
            <h1 class="list-title">勤怠詳細</h1>
        </div>
        <div class="detail-table-container">
            <table>
                <tr>
                    <td class="detail-table-content detail-table-gravity-left">名前</td>
                    <td class="detail-table-content">{{$postedUserName}}</td>
                    <td class="detail-table-content"></td>
                    <td class="detail-table-content"></td>
                </tr>
                <tr>
                    <td class="detail-table-content detail-table-gravity-left">日付</td>
                    <td class="detail-table-content">{{$stringReferencedAtLetterYear}}</td>
                    <td class="detail-table-content"></td>
                    <td class="detail-table-content">{{$stringReferencedAtLetterMonthDay}}</td>
                </tr>
                <tr>
                    <td class="detail-table-upper-content detail-table-gravity-left">出勤・退勤</td>
                    <td class="detail-table-upper-content">
                        <input 
                            type="text" 
                            name="{{$namePrefixDraftTimetableCheckinAt}}" 
                            class="{{$timeValueFieldClass.$currentFieldAppendingClass}}"
                            value="{{$stringValueCheckinAt}}" {{$stringFieldReadOnly}}>
                        </input>
                    </td>
                    <td class="detail-table-upper-content fromTo">〜</td>
                    <td class="detail-table-upper-content">
                        <input 
                            type="text" 
                            name="{{$namePrefixDraftTimetableCheckoutAt}}" 
                            class="{{$timeValueFieldClass.$currentFieldAppendingClass}}"
                            value="{{$stringValueCheckoutAt}}" {{$stringFieldReadOnly}}>
                        </input>
                    </td>
                </tr>
                <tr>
                    <td class="detail-table-middle-content"></td>
                    <td class="detail-table-middle-content"><span class="error-message" data-name="{{$namePrefixDraftTimetableCheckinAt}}"></span></td>
                    <td class="detail-table-middle-content"></td>
                    <td class="detail-table-middle-content"><span class="error-message" data-name="{{$namePrefixDraftTimetableCheckoutAt}}"></span></td>
                </tr>
                <tr>
                    <td class="detail-table-lower-content"></td>
                    <td class="detail-table-lower-content" colspan="3"><span class="error-message" data-name="{{$namePrefixDraftTimetableCheckinCheckout}}"></span></td>
                </tr>


                
                @for($dummyBreakTimeListNumber = 1; $dummyBreakTimeListNumber <= $maxDummyBreakTimeListNumber + 1; $dummyBreakTimeListNumber++)
                    @php
                        $dummyBreakTimeListIndex = $dummyBreakTimeListNumber - 1;
                        $stringValueBreakTimeStartAt = null;
                        $stringValueBreakTimeEndAt = null;
                        if($dummyBreakTimeListNumber <= $maxDummyBreakTimeListNumber){
                            if($redirectTimetableListType === TimetableListType::TIMETABLE_LIST){
                                $breakTimeList = $breakTimeLists[$dummyBreakTimeListNumber - 1];
                                if($breakTimeList){
                                    $stringValueBreakTimeStartAt = $breakTimeList->string_attendance_detail_break_time_start_at;
                                    $stringValueBreakTimeEndAt = $breakTimeList->string_attendance_detail_break_time_end_at;
                                }//$breakTimeList
                            }else if($redirectTimetableListType === TimetableListType::DRAFT_TIMETABLE_LIST){
                                $draftBreakTimeList = $draftBreakTimeLists[$dummyBreakTimeListNumber - 1];
                                if($draftBreakTimeList){
                                    $stringValueBreakTimeStartAt = $draftBreakTimeList->string_attendance_detail_break_time_start_at;
                                    $stringValueBreakTimeEndAt = $draftBreakTimeList->string_attendance_detail_break_time_end_at;
                                }//$draftBreakTimeList
                            }//$redirectTimetableListType
                        }//$dummyBreakTimeListNumber&$maxDummyBreakTimeListNumber

                        $stringBreakTimeLabel = null;
                        if($dummyBreakTimeListNumber === 1){
                            $stringBreakTimeLabel = "休憩";
                        }else{//$dummyBreakTimeListNumber
                            $stringBreakTimeLabel = "休憩".$dummyBreakTimeListNumber;
                        }//$dummyBreakTimeListNumber
                    @endphp
                    <tr>
                        <td  class="detail-table-upper-content detail-table-gravity-left">{{ $stringBreakTimeLabel }}</td>
                        <td  class="detail-table-upper-content">
                            <input 
                                type="text"
                                class="{{$timeValueFieldClass.$currentFieldAppendingClass}}"
                                name="{{ $namePrefixDraftTimetableBreakTimeStartAt }}[]"
                                value="{{$stringValueBreakTimeStartAt}}" {{$stringFieldReadOnly}}>
                        </td>
                        <td  class="detail-table-upper-content fromTo">〜</td>
                        <td  class="detail-table-upper-content">
                            <input
                                type="text"
                                class="{{$timeValueFieldClass.$currentFieldAppendingClass}}"
                                name="{{ $namePrefixDraftTimetableBreakTimeEndAt }}[]"
                                value="{{$stringValueBreakTimeEndAt}}" {{$stringFieldReadOnly}}>
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-table-middle-content"></td>
                        <td class="detail-table-middle-content"><span class="error-message" data-name="{{$namePrefixDraftTimetableBreakTimeStartAt}}" data-index="{{$dummyBreakTimeListIndex}}"></span></td>
                        <td class="detail-table-middle-content"></td>
                        <td class="detail-table-middle-content"><span class="error-message" data-name="{{$namePrefixDraftTimetableBreakTimeEndAt}}" data-index="{{$dummyBreakTimeListIndex}}"></span></td>
                    </tr>
                    <tr>
                        <td class="detail-table-lower-content"></td>
                        <td class="detail-table-lower-content" colspan="3"><span class="error-message" data-name="{{$namePrefixDraftTimetableBreakTimeStartBreakTimeEnd}}" data-index="{{$dummyBreakTimeListIndex}}"></span></td>
                    </tr>
                @endfor

                <tr>
                    <td class="detail-table-upper-content detail-table-gravity-left">備考</td>
                    <td class="detail-table-upper-content" colspan="3">
                        <textarea 
                            name="{{$namePrefixDraftTimetableDescription}}"
                            class="{{$descriptionFieldClass.$currentFieldAppendingClass}}"
                            {{$stringFieldReadOnly}}
                        >{{$stringValueDescription}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="detail-table-lower-content"></td>
                    <td class="detail-table-lower-content" colspan="3">
                        <span class="error-message" data-name="{{$namePrefixDraftTimetableDescription}}"></span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="draft-timetable-modal-button-container">
            <button class="{{$draftTimetableModalButtonClass.$currentDraftTimetableModalButtonAppendingClass}}" 
                id="{{$draftTimetableModalButtonId}}" {{$stringDraftTimetableModalButtonDisabled}}>
                {{$currentDraftTimetableModalButtonTag}}
            </button>

            <div class="{{$draftTimetableModalButtonMessageClass.$currentDraftTimetableModalButtonMessageAppendingClass}}" 
                id="{{$draftTimetableModalButtonMessageId}}">
                {{$currentDraftTimetableModalButtonMessageTag}}
            </div>
        </div>
    </div>

    <div id="{{ $draftTimetableModalId }}" class="draft-timetable-modal" style="display: none;">
        <div class="draft-timetable-modal-content">
            <button id = {{$draftTimetableCloseButtonId}}></button>
            <p id="{{ $draftTimetableModalMessageId }}"></p>
            <div id="{{ $draftTimetableModalTableId }}" class="draft-timetable-modal-body">
                <!-- テーブルやコンテンツ -->
            </div>
            <button class="{{ $draftTimetableSubmitButtonClass }}" id="{{ $draftTimetableSubmitButtonId }}">
                決定
            </button>
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

        window.draftTimetableModalHandlerActionGroup = {
            currentDummyTimetableListReferencedAt:@json($currentDummyTimetableListReferencedAt),
            timeParseErrorStatusNoTimeFormatMessage:@json($timeParseErrorStatusNoTimeFormatMessage),
            draftTimetableModalButtonClass:@json($draftTimetableModalButtonClass),
            draftTimetableModalButtonMessageClass:@json($draftTimetableModalButtonMessageClass),
            draftTimetableSubmitButtonClass:@json($draftTimetableSubmitButtonClass),
            routeDraftTimetableModal:@json($routeDraftTimetableModal),
            routeDraftTimetableSubmit:@json($routeDraftTimetableSubmit),
            routeDraftTimetableUpdate:@json($routeDraftTimetableUpdate),
            routeDraftTimetableReplace:@json($routeDraftTimetableReplace),
            routeAttendanceList:@json($routeAttendanceList),
            routeStampCorrectionRequestList:@json($routeStampCorrectionRequestList),
            routeAdminAttendanceList:@json($routeAdminAttendanceList),
            routeAdminAttendanceStaffId:@json($routeAdminAttendanceStaffId),
            namePrefixDraftTimetableCheckinAt:@json($namePrefixDraftTimetableCheckinAt),
            namePrefixDraftTimetableCheckoutAt:@json($namePrefixDraftTimetableCheckoutAt),
            namePrefixDraftTimetableBreakTimeStartAt:@json($namePrefixDraftTimetableBreakTimeStartAt),
            namePrefixDraftTimetableBreakTimeEndAt:@json($namePrefixDraftTimetableBreakTimeEndAt),
            namePrefixDraftTimetableDescription:@json($namePrefixDraftTimetableDescription),
            namePrefixDraftTimetableCheckinCheckout:@json($namePrefixDraftTimetableCheckinCheckout),
            namePrefixDraftTimetableBreakTimeStartBreakTimeEnd:@json($namePrefixDraftTimetableBreakTimeStartBreakTimeEnd),
            draftTimetableModalButtonMessageId:@json($draftTimetableModalButtonMessageId),
            draftTimetableModalId:@json($draftTimetableModalId),
            draftTimetableModalMessageId:@json($draftTimetableModalMessageId),
            timeValueFieldClass:@json($timeValueFieldClass),
            descriptionFieldClass:@json($descriptionFieldClass),
            disabledFieldClass:@json($disabledFieldClass),
        }
        
    </script>
    
    <script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection