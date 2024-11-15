<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_qualifications', function (Blueprint $table) {
            $table->id('job_qualification_id'); 
            $table->string('company_en', 255);
            $table->string('company_th', 255); 
            $table->string('work_place_en', 255); 
            $table->string('work_place_th', 255);
            $table->string('working_day_en', 255);
            $table->string('working_day_th', 255); 
            $table->string('day_off_en', 255);
            $table->string('day_off_th', 255); 
            $table->text('benefits_en');
            $table->text('benefits_th');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_qualifications');
    }
};
