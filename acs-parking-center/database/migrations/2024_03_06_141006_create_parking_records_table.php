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
        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();
            $table->string("parking_pass_code")->uniqid();
            $table->string("parking_pass_type");
            $table->string("carin_datetime")->nullable();
            $table->string("carout_datetime")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_records');
    }
};
