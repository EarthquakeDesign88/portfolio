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
        Schema::create('positions', function (Blueprint $table) {
            $table->id('position_id'); 
            $table->string('position_desc_en', 255);
            $table->string('position_desc_th', 255);
            $table->string('position_status', 50); 
            $table->foreignId('position_department_id')->constrained('departments', 'department_id');
            $table->foreignId('position_job_qualification_id')->constrained('job_qualifications', 'job_qualification_id');
            $table->foreignId('position_mode_id')->constrained('work_modes', 'mode_id');
            $table->text('responsibilities_en');
            $table->text('responsibilities_th');
            $table->text('knowledge_skills_en');
            $table->text('knowledge_skills_th');
            $table->text('require_feature_en');
            $table->text('require_feature_th');
            $table->decimal('salary', 10, 2);
            $table->integer('vacancies');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
