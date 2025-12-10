<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progressions', function (Blueprint $table) {
            if (!Schema::hasColumn('progressions', 'current_substep')) {
                $table->integer('current_substep')->default(0);
            }
            if (!Schema::hasColumn('progressions', 'resume_key')) {
                $table->string('resume_key')->nullable();
            }
            if (!Schema::hasColumn('progressions', 'resume_value')) {
                $table->string('resume_value')->nullable();
            }
            if (!Schema::hasColumn('progressions', 'log')) {
                $table->text('log')->nullable();
            }
            if (!Schema::hasColumn('progressions', 'retries')) {
                $table->integer('retries')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('progressions', function (Blueprint $table) {
            $table->dropColumn(['current_substep','resume_key','resume_value','log','retries']);
        });
    }
}
