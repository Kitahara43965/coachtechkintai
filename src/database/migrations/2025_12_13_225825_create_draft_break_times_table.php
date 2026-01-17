<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftBreakTimesTable extends Migration
{
    public function up()
    {
        Schema::create('draft_break_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draft_timetable_id')->constrained()->cascadeOnDelete();
            $table->timestamp('break_time_start_at')->nullable();
            $table->timestamp('break_time_end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('draft_break_times');
    }
}
