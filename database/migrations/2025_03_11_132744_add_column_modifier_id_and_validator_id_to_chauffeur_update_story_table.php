<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnModifierIdAndValidatorIdToChauffeurUpdateStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chauffeur_update_story', function (Blueprint $table) {
            $table->unsignedBigInteger('modifier_id')->nullable();
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('modifier_id')->references('id')->on('users'); 
            $table->foreign('validator_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chauffeur_update_story', function (Blueprint $table) {
            $table->dropColumn('modifier_id');
            $table->dropColumn('validator_id');
        });
    }
}
