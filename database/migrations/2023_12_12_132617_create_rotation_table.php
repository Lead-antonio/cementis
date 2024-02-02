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
            $table->string('imei')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('vehicule')->nullable();
            $table->timestamp('date_heure')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
