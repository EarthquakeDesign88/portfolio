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
        Schema::create('company_setup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(false);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            $table->unsignedBigInteger('stamp_id')->nullable(false);
            $table->foreign('stamp_id')->references('id')->on('stamps')->onDelete('cascade');
            
            $table->unsignedBigInteger('place_id')->nullable(false);
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            
            $table->unsignedBigInteger('floor_id')->nullable(false);
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            
            $table->unsignedInteger('total_quota')->nullable(false);
            $table->unsignedInteger('remaining_quota')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_setup');
    }
};
