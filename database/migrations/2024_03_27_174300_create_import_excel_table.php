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
        Schema::create('import_excel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_importation')->nullable();
            $table->string('rfid_chauffeur')->nullable();
            $table->string('camion')->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->decimal('delais_route')->nullable();
            $table->string('sigdep_reel')->nullable();
            $table->string('marche')->nullable();
            $table->string('adresse_livraison')->nullable();
            $table->unsignedInteger('import_calendar_id');
            $table->string('imei')->nullable();
            $table->foreign('import_calendar_id')->references('id')->on('import_calendar')->onDelete('cascade');
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
        Schema::drop('import_excel');
    }
}
