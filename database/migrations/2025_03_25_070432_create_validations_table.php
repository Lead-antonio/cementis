<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('model_type'); 
            $table->enum('action_type',['create', 'update', 'delete']); 
            $table->unsignedBigInteger('model_id'); // ID du modèle en attente
            $table->json('modifications')->nullable(); // Stocke les changements sous forme JSON
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('commentaire')->nullable();
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('validations');
    }
}
