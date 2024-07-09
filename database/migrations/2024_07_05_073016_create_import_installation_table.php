<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportInstallationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_installation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transporteur_nom')->nullable();
            $table->string('transporteur_adresse')->nullable();
            $table->string('transporteur_tel')->nullable();
            $table->string('chauffeur_nom')->nullable();
            $table->string('chauffeur_rfid')->nullable();
            $table->string('chauffeur_contact')->nullable();
            $table->string('vehicule_nom')->nullable();
            $table->string('vehicule_imei')->nullable();
            $table->text('vehicule_description')->nullable();
            $table->string('installateur_matricule')->nullable();
            $table->date('dates')->nullable();
            $table->unsignedInteger('import_name_id');
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
        Schema::drop('import_installation');
    }
}
