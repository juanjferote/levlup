<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon'); // nombre del icono o ruta de la imagen
            $table->enum('condition_type', ['streak', 'tasks_completed', 'habits_completed', 'points']); // tipo de condición para desbloquear la insignia
            $table->integer('condition_value'); // valor que hay que alcanzar para desbloquearla
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};