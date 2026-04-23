<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// redirigir la raíz al landing page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
