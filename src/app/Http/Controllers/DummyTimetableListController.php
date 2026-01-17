<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\TimetableListType;
use Carbon\Carbon;
use App\Services\List\DummyTimetableListService;

class DummyTimetableListController extends Controller
{
    public function updateDummyTimetableList(Request $request)
    {
        $timetableListArrays = $request->input("timetableListArrays"); 
        $draftTimetableListArrays = $request->input("draftTimetableListArrays");
        $stringISODateNow = $request->input("stringISODateNow");
        if($stringISODateNow){
            $carbonNow = Carbon::parse($stringISODateNow);
        }else{//$stringISODateNow
            $carbonNow = Carbon::now();
        }//$stringISODateNow

        $newTimetableLists = DummyTimetableListService::getCurrentDummyTimetableListsFromDummyTimetableListArrays(
            TimetableListType::TIMETABLE_LIST,
            $timetableListArrays,
            $carbonNow
        );

        $newDraftTimetableLists = DummyTimetableListService::getCurrentDummyTimetableListsFromDummyTimetableListArrays(
            TimetableListType::DRAFT_TIMETABLE_LIST,
            $draftTimetableListArrays,
            $carbonNow
        );

        $results = [
            "newTimetableLists" => $newTimetableLists,
            "newDraftTimetableLists" => $newDraftTimetableLists,
        ];

        return response()->json($results, 200);
    }//updateDraftTimetableList
}
