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
        Schema::table('parking_records', function (Blueprint $table) {
            $table->string('stamp_code_id')->nullable(true)->after('license_plate_path');
            $table->integer('num_stamp')->nullable(true)->after('stamp_code_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parking_records', function (Blueprint $table) {
            $table->dropColumn('stamp_code_id');
            $table->dropColumn('num_stamp');
        });
    }
};
