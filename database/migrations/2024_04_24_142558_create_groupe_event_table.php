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
        if (!Schema::hasTable('groupe_event')) {
            Schema::create('groupe_event', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('imei');
                $table->string('chauffeur');
                $table->string('vehicule');
                $table->string('type');
                $table->decimal('latitude', 8, 2);
                $table->decimal('longitude', 8, 2);
                $table->integer('duree');
                $table->timestamps();
                $table->softDeletes();
            });
        }
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


