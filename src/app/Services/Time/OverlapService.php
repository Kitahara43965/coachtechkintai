<?php
namespace App\Services\Time;
use Carbon\Carbon;

class OverlapService{
    public static function getIsOverlapped(
        $firstStartTimeAt,
        $firstEndTimeAt,
        $secondStartTimeAt,
        $secondEndTimeAt
    ){
        $isComparisonDenial = false;
        if(!($firstStartTimeAt instanceof Carbon)){
            $isComparisonDenial = true;
        }else if(!($firstEndTimeAt instanceof Carbon)){
            $isComparisonDenial = true;
        }else if(!($secondStartTimeAt instanceof Carbon)){
            $isComparisonDenial = true;
        }else if(!($secondEndTimeAt instanceof Carbon)){
            $isComparisonDenial = true;
        }

        $isOverlapped = false;

        if($isComparisonDenial === false){
            if($firstStartTimeAt -> lte($secondStartTimeAt)){
                if($firstEndTimeAt -> lte($secondStartTimeAt)){
                }else{//$firstEndTimeAt
                    $isOverlapped = true;
                }//$firstEndTimeAt
            }
            if($secondStartTimeAt -> lte($firstStartTimeAt)){
                if($secondEndTimeAt -> lte($firstStartTimeAt)){
                }else{//$firstStartTimeAt
                    $isOverlapped = true;
                }//$firstEndTimeAt
            }//$firstStartTimeAt
        }//$isComparisonDenial

        return($isOverlapped);

    }//getIsOverlapped
}//OverlapService