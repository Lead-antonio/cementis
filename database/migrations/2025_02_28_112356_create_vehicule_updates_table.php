<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculeUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicule_updates', function (Blueprint $table) {
            $table->id();
            $table->string('imei')->nullable();
            $table->string('nom');
            $table->dateTime('date_installation')->nullable();
            $table->unsignedInteger('id_transporteur')->nullable();
            $table->unsignedInteger('vehicule_id')->nullable();
            $table->foreign('id_transporteur')->references('id')->on('transporteur');
            $table->foreign('vehicule_id')->references('id')->on('vehicule');
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
        Schema::dropIfExists('vehicule_updates');
    }
}
