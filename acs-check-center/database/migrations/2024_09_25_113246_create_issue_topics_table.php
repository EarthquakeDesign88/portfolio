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
        Schema::create('issue_topics', function (Blueprint $table) {
            $table->id('issue_id');
            $table->string('issue_description');
            $table->unsignedBigInteger('supervisor_id');
            $table->timestamps();

            $table->foreign('supervisor_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_topics');
    }
};
