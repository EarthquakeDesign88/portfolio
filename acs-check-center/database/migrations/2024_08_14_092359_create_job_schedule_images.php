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
        Schema::create('job_schedule_images', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('job_schedule_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('job_schedule_id')->references('job_schedule_id')->on('job_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_schedule_images');
    }
};
