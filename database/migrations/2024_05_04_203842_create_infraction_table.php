<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfractionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id')->unsigned()->nullable();
            $table->string('imei')->nullable();
            $table->string('rfid')->nullable();
            $table->string('vehicule')->nullable();
            $table->string('event')->nullable();
            $table->decimal('distance')->nullable();
            $table->decimal('odometer')->nullable();
            $table->integer('duree_infraction')->nullable();
            $table->integer('duree_initial')->nullable();
            $table->string('date_debut')->nullable();
            $table->string('date_fin')->nullable();
            $table->string('heure_debut')->nullable();
            $table->string('heure_fin')->nullable();
            $table->string('gps_debut')->nullable();
            $table->string('gps_fin')->nullable();
            $table->decimal('point')->nullable();
            $table->integer('insuffisance')->nullable();
            $table->decimal('distance_calendar')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('calendar_id')->references('id')->on('import_excel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('infraction');
    }
}
