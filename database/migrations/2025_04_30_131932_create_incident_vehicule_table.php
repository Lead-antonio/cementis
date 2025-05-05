<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentVehiculeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_vehicule', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vehicule_id')->nullable();
            $table->unsignedInteger('chauffeur_id')->nullable();
            $table->string('imei_vehicule')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->double('distance_parcourue')->nullable();
            $table->double('vitesse_maximale')->nullable();
            $table->double('vitesse_moyenne')->nullable();
            $table->double('duree_arret')->nullable();
            $table->double('duree_repos')->nullable();
            $table->integer('duree_conduite')->nullable();
            $table->double('duree_travail')->nullable();
            $table->foreign('vehicule_id')->references('id')->on('vehicule');
            $table->foreign('chauffeur_id')->references('id')->on('chauffeur');
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
        Schema::drop('incident_vehicule');
    }
}
