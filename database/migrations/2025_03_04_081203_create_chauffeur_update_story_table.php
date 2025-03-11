<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChauffeurUpdateStoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeur_update_story', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chauffeur_id')->nullable();
            $table->unsignedInteger('chauffeur_update_type_id')->nullable();
            $table->unsignedInteger('transporteur_id')->nullable();
            $table->string('rfid')->nullable();
            $table->string('nom')->nullable();
            $table->string('contact')->nullable();
            $table->longText('commentaire')->nullable();
            $table->string('rfid_physique')->nullable();
            $table->string('numero_badge')->nullable();
            $table->boolean('validation')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('transporteur_id')->references('id')->on('transporteur'); 
            $table->foreign('chauffeur_id')->references('id')->on('chauffeur')->onDelete('cascade');
            $table->foreign('chauffeur_update_type_id')->references('id')->on('chauffeur_update_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chauffeur_update_story');
    }
}
