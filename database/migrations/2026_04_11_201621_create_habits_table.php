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
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // clave foránea hacia users
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('target_per_week'); // días a la semana que el usuario se propone cumplir el hábito (1-7)
            $table->json('custom_days')->nullable(); // días concretos de la semana si el usuario los quiere fijar, ej: [1,3,5]
            $table->boolean('active')->default(true); // si el hábito está activo o archivado
            $table->boolean('suggested_by_system')->default(false); // indica si el hábito proviene de una sugerencia del sistema
            $table->integer('difficulty_level')->nullable(); // nivel de dificultad (1-5) si es un hábito sugerido, null si es personalizado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
