<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChauffeurUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeur_updates', function (Blueprint $table) {
            $table->id();
            $table->string('rfid')->nullable();
            $table->string('nom');
            $table->string('rfid_physique')->nullable();
            $table->string('numero_badge')->nullable();
            $table->string('contact')->nullable();
            $table->unsignedInteger('chauffeur_id')->nullable();
            $table->unsignedInteger('transporteur_id')->nullable();
            $table->date('date_installation')->nullable();
            $table->foreign('transporteur_id')->references('id')->on('transporteur'); 
            $table->foreign('chauffeur_id')->references('id')->on('chauffeur'); 
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
        Schema::dropIfExists('chauffeur_updates');
    }
}
