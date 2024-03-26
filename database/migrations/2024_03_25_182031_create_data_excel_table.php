<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataExcelTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_importation');
            $table->string('rfid_chauffeur');
            $table->string('camion');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->decimal('delais_route');
            $table->string('sigdep_reel');
            $table->string('marche');
            $table->string('adresse_livraison');
            $table->timestamps();
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
        Schema::drop('import_calendar');
    }
}
