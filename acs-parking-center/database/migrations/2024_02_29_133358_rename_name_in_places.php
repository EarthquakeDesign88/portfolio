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
        if (Schema::hasColumn('places', 'name')) {
            Schema::table('places', function (Blueprint $table) {
                $table->renameColumn('name', 'place_name');
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
        if (Schema::hasColumn('places', 'place_name')) {
            Schema::table('places', function (Blueprint $table) {
                $table->renameColumn('name', 'place_name');
            });
        }
    }
};
