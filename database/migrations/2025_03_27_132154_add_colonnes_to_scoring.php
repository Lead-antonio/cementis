<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColonnesToScoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scoring', function (Blueprint $table) {
            $table->string('badge_rfid')->nullable();
            $table->string('badge_calendar')->nullable();
            $table->string('imei')->nullable();
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
            $table->dropColumn('badge_rfid'); // Supprime 'colonne1'
            $table->dropColumn('badge_calendar'); 
            $table->dropColumn('imei'); 
        });
    }
}
