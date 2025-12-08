<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imei')->nullable();
            $table->string('nom');
            $table->unsignedInteger('id_transporteur')->nullable();
            $table->foreign('id_transporteur')->references('id')->on('transporteur');
            $table->foreign('id_planning')->references('id')->on('import_calendar'); 
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
        Schema::drop('vehicule');
    }
}

