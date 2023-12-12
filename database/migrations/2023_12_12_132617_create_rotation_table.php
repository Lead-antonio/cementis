<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRotationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule')->nullable();
            $table->string('mouvement')->nullable();
            $table->timestamp('date_heur')->nullable();
            $table->string('coordonne_gps')->nullable();
            $table->string('adresse')->nullable();
            $table->string('tranche')->nullable();
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
        Schema::drop('rotation');
    }
}
