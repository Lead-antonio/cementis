<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePenaliteChauffeurTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalite_chauffeur', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_chauffeur')->unsigned()->nullable();
            $table->integer('id_calendar')->unsigned()->nullable();
            $table->integer('id_event')->unsigned()->nullable();
            $table->integer('id_penalite')->unsigned()->nullable();
            $table->dateTime('date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Définition des clés étrangères
            $table->foreign('id_chauffeur')->references('id')->on('chauffeur')->onDelete('cascade');
            $table->foreign('id_calendar')->references('id')->on('import_excel')->onDelete('cascade');
            $table->foreign('id_event')->references('id')->on('event')->onDelete('cascade');
            $table->foreign('id_penalite')->references('id')->on('penalite')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('penalite_chauffeur');
    }
}
