<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRfidPhysiqueAndNumberBadgeToChauffeurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chauffeur', function (Blueprint $table) {
            $table->string('rfid_physique')->nullable();
            $table->string('numero_badge')->nullable();
        });
    }
            
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chauffeur', function (Blueprint $table) {
            $table->dropColumn('rfid_physique');
            $table->dropColumn('numero_badge');
        });
    }
}
