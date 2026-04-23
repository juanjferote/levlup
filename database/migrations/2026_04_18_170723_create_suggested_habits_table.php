<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggested_habits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // categoría que coincide con los intereses del usuario (deporte, lectura, etc.)
            $table->integer('difficulty_level'); // nivel de dificultad del 1 al 5
            $table->integer('suggested_target_per_week'); // días a la semana sugeridos para este hábito
            $table->integer('suggested_duration_minutes')->nullable(); // duración sugerida en minutos (opcional)
            $table->timestamps();

            // índice para acelerar la búsqueda por categoría y dificultad (usado en sugerencias)
            $table->index(['category', 'difficulty_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggested_habits');
    }
};