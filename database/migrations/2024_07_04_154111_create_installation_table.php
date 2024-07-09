<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installation', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date_installation');  
            $table->unsignedInteger('vehicule_id');
            $table->unsignedInteger('installateur_id');
            $table->foreign('installateur_id')->references('id')->on('installateur');
            $table->foreign('vehicule_id')->references('id')->on('vehicule');
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
        Schema::drop('installation');
    }
}
