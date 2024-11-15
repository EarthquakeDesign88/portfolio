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
        Schema::table('members', function (Blueprint $table) {
            // Check if the column already exists
            if (!Schema::hasColumn('members', 'company_id')) {
                // Add the new column
                $table->unsignedBigInteger('company_id')->nullable(false)->after('place_id');
                $table->foreign('company_id')->references('id')->on('companies');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            // Check if the column exists before dropping it
            if (Schema::hasColumn('members', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }
};
