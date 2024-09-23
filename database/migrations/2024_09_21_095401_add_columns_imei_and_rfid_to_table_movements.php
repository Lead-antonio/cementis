<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsImeiAndRfidToTableMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movement', function (Blueprint $table) {
            //
            $table->string('imei')->nullable();
            $table->string('rfid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movement', function (Blueprint $table) {
            //
            $table->dropColumn('imei');
            $table->dropColumn('rfid');
        });
    }
}
