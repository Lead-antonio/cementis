<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBadgeChauffeurColonneToImportExcel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_excel', function (Blueprint $table) {
            $table->string('badge_chauffeur')->nullable()->after('rfid_chauffeur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_excel', function (Blueprint $table) {
            $table->dropColumn('badge_chauffeur');
        });
    }
}
