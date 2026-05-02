<?php

use App\Models\User;
use App\Services\NivelService;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('usuario empieza en nivel 1 con 0 puntos', function () {
    $user = User::factory()->make(['points' => 0, 'level' => 1]);

    expect($user->calculateLevel())->toBe(1);
});

test('usuario sube a nivel 2 con 100 puntos', function () {
    $user = User::factory()->make(['points' => 100, 'level' => 1]);

    expect($user->calculateLevel())->toBe(2);
});

test('usuario sube a nivel 3 con 300 puntos', function () {
    $user = User::factory()->make(['points' => 300, 'level' => 2]);

    expect($user->calculateLevel())->toBe(3);
});

test('usuario no supera nivel 10 con muchos puntos', function () {
    $user = User::factory()->make(['points' => 999999, 'level' => 10]);

    expect($user->calculateLevel())->toBe(10);
});

test('pointsToNextLevel devuelve 0 en nivel maximo', function () {
    $user = User::factory()->make(['points' => 999999, 'level' => 10]);

    expect($user->pointsToNextLevel())->toBe(0);
});

test('pointsToNextLevel calcula correctamente en nivel 1', function () {
    $user = User::factory()->make(['points' => 0, 'level' => 1]);

    // para nivel 2 hacen falta 100 puntos, tiene 0, faltan 100
    expect($user->pointsToNextLevel())->toBe(100);
});

test('pointsToNextLevel calcula correctamente en nivel 2', function () {
    $user = User::factory()->make(['points' => 150, 'level' => 2]);

    // para nivel 3 hacen falta 300 puntos acumulados, tiene 150, faltan 150
    expect($user->pointsToNextLevel())->toBe(150);
});

test('datosProgreso devuelve estructura correcta', function () {
    $service = new NivelService();
    $user    = User::factory()->make(['points' => 50, 'level' => 1]);

    $datos = $service->datosProgreso($user);

    expect($datos)->toHaveKeys(['nivel', 'puntos', 'porcentajeNivel', 'faltan']);
    expect($datos['nivel'])->toBe(1);
    expect($datos['puntos'])->toEqual(50);
    expect($datos['faltan'])->toEqual(50);
    expect($datos['porcentajeNivel'])->toEqual(50);
});

test('addPoints sube de nivel cuando corresponde', function () {
    $user = User::factory()->create(['points' => 90, 'level' => 1]);

    $subioNivel = $user->addPoints(10);

    expect($subioNivel)->toBeTrue();
    expect($user->fresh()->level)->toBe(2);
});

test('addPoints no sube de nivel si no corresponde', function () {
    $user = User::factory()->create(['points' => 0, 'level' => 1]);

    $subioNivel = $user->addPoints(10);

    expect($subioNivel)->toBeFalse();
    expect($user->fresh()->level)->toBe(1);
});