<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\StatisticsController;

// redirigir la raíz al landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // panel de control
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // tareas (misiones)
    Route::resource('tareas', TaskController::class)->except(['show']);
    Route::patch('tareas/{tarea}/completar', [TaskController::class, 'completar'])->name('tareas.completar');

    // hábitos
    Route::resource('habitos', HabitController::class)->except(['show']);
    Route::patch('habitos/{habito}/registrar', [HabitController::class, 'registrar'])->name('habitos.registrar');

    // perfil
    Route::get('perfil', [ProfileController::class, 'index'])->name('perfil.index');
    Route::patch('perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::post('perfil/intereses', [ProfileController::class, 'update'])->name('perfil.intereses');
    Route::patch('perfil/password', [ProfileController::class, 'updatePassword'])->name('perfil.password');

    // sugerencias de hábitos
    Route::get('sugerencias', [SuggestionController::class, 'index'])->name('sugerencias.index');
    Route::get('sugerencias/{sugerencia}', [SuggestionController::class, 'show'])->name('sugerencias.show');
    Route::post('sugerencias/{sugerencia}/añadir', [SuggestionController::class, 'añadir'])->name('sugerencias.añadir');

    // insignias
    Route::get('insignias', [BadgeController::class, 'index'])->name('insignias.index');

    // estadísticas
    Route::get('estadisticas', [StatisticsController::class, 'index'])->name('estadisticas.index');
});

require __DIR__ . '/auth.php';
