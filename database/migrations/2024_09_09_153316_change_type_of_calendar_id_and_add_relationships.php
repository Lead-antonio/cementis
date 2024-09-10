<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeOfCalendarIdAndAddRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movement', function (Blueprint $table) {
            $table->unsignedInteger('calendar_id')->change(); // Ajoute la colonne
            $table->foreign('calendar_id')->references('id')->on('import_excel'); // Définit la clé étrangère avec suppression en cascade
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movement', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']); // Supprime la clé étrangère
            $table->integer('calendar_id')->change();
        });
    }
}
