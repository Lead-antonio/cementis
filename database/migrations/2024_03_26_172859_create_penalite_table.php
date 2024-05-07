<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaliteTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalite', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event')->nullable();
            $table->integer('point_penalite');
            $table->integer('duree')->default(0);
            $table->integer('duree_heure')->default(0);
            $table->integer('duree_minute')->default(0);
            $table->integer('duree_seconde')->default(0);
            $table->integer('default_value')->default(0);
            $table->integer('param')->default(0);
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
        Schema::drop('penalite');
    }
}
