<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportExcelTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Import_excel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_importation');
            $table->string('rfid_chauffeur');
            $table->string('camion');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->decimal('delais_route');
            $table->string('sigdep_reel');
            $table->string('marche');
            $table->string('adresse_livraison');
            $table->unsignedInteger('import_calendar_id');
            $table->foreign('import_calendar_id')->references('id')->on('Import_calendar')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Import_excel');
    }
}
