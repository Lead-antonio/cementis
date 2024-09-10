<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id');
            $table->date('start_date');
            $table->time('start_hour');
            $table->date('end_date');
            $table->time('end_hour');
            $table->time('duration');
            $table->enum('type', ['DRIVE', 'STOP']);
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
        Schema::drop('movement');
    }
}
