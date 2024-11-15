<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_schedules', function (Blueprint $table) {
            $table->id('job_schedule_id'); 
            $table->unsignedBigInteger('job_authority_id'); 
            $table->date('job_schedule_date');
            $table->unsignedBigInteger('job_schedule_shift_id'); 
            $table->unsignedBigInteger('job_schedule_status_id');
            $table->timestamps(); 

            $table->foreign('job_authority_id')->references('user_authority_id')->on('user_authorities')->onDelete('cascade');
            $table->foreign('job_schedule_shift_id')->references('work_shift_id')->on('work_shifts')->onDelete('cascade');
            $table->foreign('job_schedule_status_id')->references('job_status_id')->on('job_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_schedules');
    }
};
