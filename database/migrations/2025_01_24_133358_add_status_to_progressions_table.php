<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progressions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed', 'error'])->default('pending')->after('month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progressions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
