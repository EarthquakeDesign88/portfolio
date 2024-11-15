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
        Schema::table('job_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('job_schedule_issue_id')->after('job_schedule_status_id')->default(0);
            
            $table->foreign('job_schedule_issue_id')->references('issue_id')->on('issue_topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('job_schedules', 'job_schedule_issue_id')) {
                $table->dropForeign(['job_schedule_issue_id']);
                $table->dropColumn('job_schedule_issue_id');  
            }
        });
    }
};