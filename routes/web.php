<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;

// redirigir la raíz al landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // tareas (misiones)
    Route::resource('tareas', TaskController::class)->except(['show']);
    Route::patch('tareas/{task}/completar', [TaskController::class, 'completar'])->name('tareas.completar');

});

require __DIR__ . '/auth.php';