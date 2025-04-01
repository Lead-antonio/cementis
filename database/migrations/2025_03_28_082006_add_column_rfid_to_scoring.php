<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRfidToScoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scoring', function (Blueprint $table) {
            $table->string('rfid_chauffeur')->nullable();
            $table->string('rfid_infraction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scoring', function (Blueprint $table) {
            $table->dropColumn('rfid_chauffeur'); // Supprime 'colonne1'
            $table->dropColumn('rfid_infraction');
        });
    }
}
