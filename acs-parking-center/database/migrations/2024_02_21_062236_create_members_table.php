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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique()->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('phone', 15)->nullable(false); 
            $table->enum('member_status', ['active', 'inactive'])->nullable(false);
            $table->unsignedBigInteger('member_type_id')->nullable(false);
            $table->unsignedBigInteger('place_id')->nullable(false);
            $table->string('id_card', 13)->nullable(false); 
            $table->string('license_driver', 20)->nullable(true); 
            $table->string('license_plate', 20)->nullable(true); 
            $table->date('issue_date')->nullable(true); 
            $table->date('expiry_date')->nullable(true); 
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('member_type_id')->references('id')->on('member_types');
            $table->foreign('place_id')->references('id')->on('places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
