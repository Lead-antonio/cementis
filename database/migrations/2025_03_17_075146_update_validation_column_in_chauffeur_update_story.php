<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValidationColumnInChauffeurUpdateStory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chauffeur_update_story', function (Blueprint $table) {
            $table->integer('validation')->default(1)->change();
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
            $table->boolean('validation')->default(false)->change();
        });
    }
}
