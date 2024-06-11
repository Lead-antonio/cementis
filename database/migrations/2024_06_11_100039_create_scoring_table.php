<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoringTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scoring', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_planning')->nullable();
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('transporteur_id')->nullable();
            $table->string('camion')->nullable();
            $table->longText('comment')->nullable();
            $table->decimal('distance')->nullable();
            $table->decimal('point')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('driver_id')->references('id')->on('chauffeur')->onDelete('cascade');
            $table->foreign('transporteur_id')->references('id')->on('transporteur')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scoring');
    }
}
