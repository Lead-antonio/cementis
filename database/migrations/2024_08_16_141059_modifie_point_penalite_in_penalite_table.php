<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiePointPenaliteInPenaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penalite', function (Blueprint $table) {
            $table->decimal('point_penalite', 8, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penalite', function (Blueprint $table) {
            $table->integer('point_penalite')->change();
        });
    }
}
