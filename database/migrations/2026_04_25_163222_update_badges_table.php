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
        Schema::table('badges', function (Blueprint $table) {
            // añadimos rareza
            $table->enum('rarity', ['comun', 'rara', 'epica', 'legendaria'])->default('comun')->after('icon');

            // añadimos categoría para insignias de hábitos
            $table->string('category')->nullable()->after('rarity');

            // ampliamos los tipos de condición
            $table->dropColumn('condition_type');
        });

        Schema::table('badges', function (Blueprint $table) {
            $table->enum('condition_type', [
                'tasks_completed',   // tareas completadas
                'habit_hacer',       // cumplimientos totales de hábitos hacer
                'habit_dejar',       // días sin fallar en hábitos dejar
                'habit_category',    // semanas consecutivas en una categoría
                'diversity',         // hábitos activos en X categorías
                'custom_interests',  // intereses personalizados añadidos
                'level',             // nivel del personaje
                'global_streak',     // días consecutivos de actividad global
            ])->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn(['rarity', 'category']);
            $table->dropColumn('condition_type');
        });

        Schema::table('badges', function (Blueprint $table) {
            $table->enum('condition_type', ['streak', 'tasks_completed', 'habits_completed', 'points']);
        });
    }
};
