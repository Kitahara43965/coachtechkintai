@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp-correction-request-list.css') }}">
@endsection

@php
    use App\Constants\UserRole;
    use App\Constants\Message;




@endphp


@section('content')

    <div class="stamp-correction-request-list-board">

        <div class="list-title-container">
            <h1 class="list-title">申請一覧</h1>
        </div>

        <div class="toggle-admission">
            @if($stampCorrectionRequestListIsAdmitted === true)
                <a class = "toggle-admission-not-selected" href="{{route('redirectToStampCorrectionRequestListNotAdmittedUpdate')}}">{{Message::STRING_IS_DRAFT_TIMETABLE_NOT_ADMITTED}}</a>
                <a class = "toggle-admission-selected" href="{{route('redirectToStampCorrectionRequestListAdmittedUpdate')}}">{{Message::STRING_IS_DRAFT_TIMETABLE_ADMITTED}}</a>
            @else
                <a class = "toggle-admission-selected" href="{{route('redirectToStampCorrectionRequestListNotAdmittedUpdate')}}">{{Message::STRING_IS_DRAFT_TIMETABLE_NOT_ADMITTED}}</a>
                <a class = "toggle-admission-not-selected" href="{{route('redirectToStampCorrectionRequestListAdmittedUpdate')}}">{{Message::STRING_IS_DRAFT_TIMETABLE_ADMITTED}}</a>
            @endif
        </div>

        <div class="underline"></div>

        <div class="list-table-container">
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="list-table-header">状態</th>
                        <th class="list-table-header">名前</th>
                        <th class="list-table-header">対象日時</th>
                        <th class="list-table-header">申請理由</th>
                        <th class="list-table-header">申請日時</th>
                        <th class="list-table-header">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @if($draftTimetableLists)
                        @foreach($draftTimetableLists as $draftTimetableList)
                            <tr>
                                <td class="list-table-content"><div>{{$draftTimetableList->string_is_admitted}}</div></td>
                                <td class="list-table-content"><div>{{$draftTimetableList->string_draft_timetable_user_name}}</div></td>
                                <td class="list-table-content"><div id = "{{$namePrefixDraftTimetableListStampCorrectionRequestListCheckinCheckoutId}}{{$loop->index}}"
                                    >{{$draftTimetableList->string_stamp_correction_request_list_checkin_checkout}}</div></td>
                                <td class="list-table-content"><div>{{$draftTimetableList->string_draft_timetable_description}}</div></td>
                                <td class="list-table-content"><div>{{$draftTimetableList->string_stamp_correction_request_list_created_at}}</div></td>
                                <td class="list-table-content">
                                    @if($bladeUserRole === UserRole::USER)
                                        <a  class ="list-table-detail" href="{{route('redirectToAttendanceDetailViaStampCorrectionRequestListForUser.id',['id'=>$draftTimetableList->id])}}">詳細</a>
                                    @elseif($bladeUserRole === UserRole::ADMIN)
                                        <a  class ="list-table-detail" href="{{route('redirectToStampCorrectionRequestApproveViaStampCorrectionRequestListForAdmin.id',
                                            ['attendance_correct_request_id'=>$draftTimetableList->id])}}">詳細</a>
                                    @endif
                                </td>  

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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

    </script>

    <script type="module" src="{{ asset('js/main.js') }}"></script>


@endsection