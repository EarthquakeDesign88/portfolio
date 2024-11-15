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
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('payment_status');
                $table->unsignedBigInteger('payment_method_id')->nullable();
                $table->foreign('payment_method_id')->references('id')->on('payment_methods');
                $table->string("paid_datetime");
                $table->string('fee');
                $table->unsignedBigInteger('parking_record_id')->nullable(false);
                $table->foreign('parking_record_id')->references('id')->on('parking_records');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};
