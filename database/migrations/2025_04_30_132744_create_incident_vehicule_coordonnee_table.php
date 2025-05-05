<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentVehiculeCoordonneeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_vehicule_coordonnee', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('incident_vehicule_id')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->dateTime('date_heure')->nullable();
            $table->double('vitesse')->nullable();
            $table->foreign('incident_vehicule_id')->references('id')->on('incident_vehicule');
            $table->softDeletes();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('incident_vehicule_coordonnee');
    }
}
