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
            $table->enum('condition_type', [
                'tasks_completed',
                'habit_hacer',
                'habit_dejar',
                'habit_category',
                'diversity',
                'custom_interests',
                'level',
                'global_streak',
                'special_task',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->enum('condition_type', [
                'tasks_completed',
                'habit_hacer',
                'habit_dejar',
                'habit_category',
                'diversity',
                'custom_interests',
                'level',
                'global_streak',
            ])->change();
        });
    }
};
