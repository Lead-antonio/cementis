<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\RunJob;

class CreateRunJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('run_jobs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_running')->default(false);
            $table->timestamps();
        });

        RunJob::create(['is_running' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('run_jobs');
    }
}
