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
        Schema::table('user_authorities', function (Blueprint $table) {
            $table->enum('user_authority_status', ['0', '1'])->after('user_location_id')->default('1');
            $table->timestamp('deleted_at')->nullable()->after('user_authority_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_authorities', function (Blueprint $table) {
            $table->dropColumn('user_authority_status');
            $table->dropColumn('deleted_at');
        });
    }
};
