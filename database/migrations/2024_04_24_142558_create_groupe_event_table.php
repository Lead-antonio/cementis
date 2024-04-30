<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupeEventTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupe_event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('imei');
            $table->string('chauffeur');
            $table->string('vehicule');
            $table->string('type');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->integer('duree');
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
        Schema::drop('groupe_event');
    }
}
