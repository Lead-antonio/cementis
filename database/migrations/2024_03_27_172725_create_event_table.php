<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imei')->nullable();
            $table->string('chauffeur')->nullable();
            $table->string('vehicule')->nullable();
            $table->string('type')->nullable();
            $table->integer('vitesse')->default(0);
            $table->decimal('latitude', 10, 2)->default(null);
            $table->decimal('longitude', 10, 2)->default(null);
            $table->decimal('odometer', 10, 2)->default(null);
            $table->longText('description')->nullable();
            $table->integer('duree')->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::drop('event');
    }
}
