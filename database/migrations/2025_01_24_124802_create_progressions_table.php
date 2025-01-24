<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('step_id'); // Référence à l'étape
            $table->string('month', 7);           // Format: YYYY-MM
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // Clé étrangère pour l'étape
            $table->foreign('step_id')->references('id')->on('processus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progressions');
    }
}
