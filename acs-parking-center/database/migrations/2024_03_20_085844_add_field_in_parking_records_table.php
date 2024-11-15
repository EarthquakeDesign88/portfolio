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
            $table->string('license_plate')->nullable(true)->after('parking_pass_type');
            $table->string('license_plate_path')->nullable(true)->after('license_plate');
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
            $table->dropColumn('license_plate');
            $table->dropColumn('license_plate_path');
        });
    }
};
