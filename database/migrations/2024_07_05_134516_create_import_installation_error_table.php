<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportInstallationErrorTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_installation_error', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->longText('contenu')->nullable();
            $table->unsignedInteger('import_name_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('import_name_id')->references('id')->on('import_name_installation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('import_installation_error');
    }
}
