<?php

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use App\Services\HabitService;

// ── HabitService::habitosActivos ──────────────────────────────────────────────

test('habito hacer pendiente aparece en habitosHacer', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create([
        'user_id'         => $user->id,
        'target_per_week' => 3,
    ]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosHacer']->contains($habito))->toBeTrue();
});

test('habito hacer registrado hoy pero sin cumplir objetivo aparece en habitosRegistrados', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create([
        'user_id'         => $user->id,
        'target_per_week' => 3,
    ]);

    $habito->logs()->create(['logged_date' => now()->toDateString()]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosRegistrados']->contains($habito))->toBeTrue();
    expect($grupos['habitosHacer']->contains($habito))->toBeFalse();
});

test('habito hacer con objetivo semanal cumplido aparece en habitosCompletados', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create([
        'user_id'         => $user->id,
        'target_per_week' => 2,
    ]);

    $habito->logs()->create(['logged_date' => now()->startOfWeek()->toDateString()]);
    $habito->logs()->create(['logged_date' => now()->startOfWeek()->addDay()->toDateString()]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosCompletados']->contains($habito))->toBeTrue();
    expect($grupos['habitosHacer']->contains($habito))->toBeFalse();
});

test('habito dejar pendiente aparece en habitosDejar', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->dejar()->create(['user_id' => $user->id]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosDejar']->contains($habito))->toBeTrue();
});

test('habito dejar registrado hoy no aparece en habitosDejar', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->dejar()->create(['user_id' => $user->id]);

    $habito->logs()->create(['logged_date' => now()->toDateString()]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosDejar']->contains($habito))->toBeFalse();
});

test('habito archivado no aparece en ningún grupo', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create([
        'user_id' => $user->id,
        'active'  => false,
    ]);

    $service = new HabitService();
    $grupos  = $service->habitosActivos($user);

    expect($grupos['habitosHacer']->contains($habito))->toBeFalse();
    expect($grupos['habitosCompletados']->contains($habito))->toBeFalse();
    expect($grupos['habitosRegistrados']->contains($habito))->toBeFalse();
});

// ── HabitService::registrarHoy ────────────────────────────────────────────────

test('registrarHoy crea un log para hoy', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create(['user_id' => $user->id]);

    $service    = new HabitService();
    $registrado = $service->registrarHoy($habito);

    expect($registrado)->toBeTrue();
    expect($habito->logs()->whereDate('logged_date', now()->toDateString())->exists())->toBeTrue();
});

test('registrarHoy devuelve false si ya está registrado hoy', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create(['user_id' => $user->id]);
    $habito->logs()->create(['logged_date' => now()->toDateString()]);

    $service    = new HabitService();
    $registrado = $service->registrarHoy($habito);

    expect($registrado)->toBeFalse();
});

// ── HabitService::calcularRachaHacer ─────────────────────────────────────────

test('calcularRachaHacer devuelve 0 si no hay logs esta semana', function () {
    $habito  = Habit::factory()->hacer()->create(['target_per_week' => 3]);
    $service = new HabitService();

    expect($service->calcularRachaHacer($habito))->toBe(0);
});

test('calcularRachaHacer devuelve 1 si se cumplió el objetivo esta semana', function () {
    $habito = Habit::factory()->hacer()->create(['target_per_week' => 2]);

    $habito->logs()->create(['logged_date' => now()->startOfWeek()->toDateString()]);
    $habito->logs()->create(['logged_date' => now()->startOfWeek()->addDay()->toDateString()]);

    $service = new HabitService();

    expect($service->calcularRachaHacer($habito))->toBe(1);
});

// ── HabitService::calcularRachaDejar ─────────────────────────────────────────

test('calcularRachaDejar devuelve dias desde creacion si nunca ha fallado', function () {
    $habito  = Habit::factory()->dejar()->create(['created_at' => now()->subDays(5)]);
    $service = new HabitService();

    expect($service->calcularRachaDejar($habito))->toBe(5);
});

test('calcularRachaDejar devuelve dias desde ultimo fallo', function () {
    $habito = Habit::factory()->dejar()->create();
    $habito->logs()->create(['logged_date' => now()->subDays(3)->toDateString()]);

    $service = new HabitService();

    expect($service->calcularRachaDejar($habito))->toBe(3);
});

// ── HabitService::otorgarXp ───────────────────────────────────────────────────

test('otorgarXp suma XP base al usuario', function () {
    $user   = User::factory()->create(['points' => 0, 'level' => 1]);
    $habito = Habit::factory()->hacer()->create([
        'user_id'         => $user->id,
        'target_per_week' => 1,
    ]);

    $service = new HabitService();
    $service->otorgarXp($user, $habito);

    expect($user->fresh()->points)->toBe(HabitService::XP_HABITO);
});

test('otorgarXp devuelve true si el usuario sube de nivel', function () {
    $user   = User::factory()->create(['points' => 90, 'level' => 1]);
    $habito = Habit::factory()->hacer()->create(['user_id' => $user->id]);

    $service    = new HabitService();
    $subioNivel = $service->otorgarXp($user, $habito);

    expect($subioNivel)->toBeTrue();
    expect($user->fresh()->level)->toBe(2);
});

// ── HTTP: autorización ────────────────────────────────────────────────────────

test('un usuario no puede registrar el habito de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();
    $habito   = Habit::factory()->hacer()->create(['user_id' => $usuario1->id]);

    $this->actingAs($usuario2)
        ->patch(route('habitos.registrar', $habito))
        ->assertStatus(403);
});

test('un usuario no puede editar el habito de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();
    $habito   = Habit::factory()->hacer()->create(['user_id' => $usuario1->id]);

    $this->actingAs($usuario2)
        ->get(route('habitos.edit', $habito))
        ->assertStatus(403);
});

test('un usuario no puede archivar el habito de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();
    $habito   = Habit::factory()->hacer()->create(['user_id' => $usuario1->id]);

    $this->actingAs($usuario2)
        ->delete(route('habitos.destroy', $habito))
        ->assertStatus(403);
});

// ── HTTP: flujo normal ────────────────────────────────────────────────────────

test('usuario autenticado puede crear un habito hacer', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('habitos.store'), [
            'title'           => 'Correr',
            'type'            => 'hacer',
            'target_per_week' => 3,
        ])
        ->assertRedirect(route('habitos.index'));

    $this->assertDatabaseHas('habits', [
        'user_id' => $user->id,
        'title'   => 'Correr',
        'type'    => 'hacer',
    ]);
});

test('usuario autenticado puede crear un habito dejar', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('habitos.store'), [
            'title' => 'Dejar de fumar',
            'type'  => 'dejar',
        ])
        ->assertRedirect(route('habitos.index'));

    $this->assertDatabaseHas('habits', [
        'user_id' => $user->id,
        'title'   => 'Dejar de fumar',
        'type'    => 'dejar',
    ]);
});

test('no se puede crear un habito hacer sin target_per_week', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('habitos.store'), [
            'title' => 'Correr',
            'type'  => 'hacer',
        ])
        ->assertSessionHasErrors('target_per_week');
});

test('archivar habito lo desactiva en base de datos', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('habitos.destroy', $habito))
        ->assertRedirect(route('habitos.index'));

    expect($habito->fresh()->active)->toBeFalse();
});