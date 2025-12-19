<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToScoreDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('score_driver', function (Blueprint $table) {
            if (!Schema::hasColumn('score_driver', 'most_infraction')) {
                $table->string('most_infraction')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('score_driver', function (Blueprint $table) {
            $table->dropColumn(['most_infraction']);
        });
    }
}
