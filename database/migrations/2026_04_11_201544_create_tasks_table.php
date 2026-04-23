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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // clave foránea hacia users
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('scheduled_at')->nullable(); // fecha y hora programada de la tarea
            $table->boolean('completed')->default(false); // si la tarea está completada o no
            $table->timestamps();
            // índice para acelerar consultas por fecha (vista de calendario, filtros)
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
