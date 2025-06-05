<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChauffeurTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeur', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rfid')->nullable();
            $table->string('nom');
            $table->string('contact')->nullable();
            $table->unsignedInteger('transporteur_id')->nullable();
            $table->unsignedInteger('id_planning')->nullable();
            $table->foreign('transporteur_id')->references('id')->on('transporteur'); 
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
        Schema::drop('chauffeur');
    }
}
