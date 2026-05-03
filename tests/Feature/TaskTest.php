<?php

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Services\BadgeService;

// ── TaskService::tareasAgrupadas ──────────────────────────────────────────────

test('tareasAgrupadas devuelve tarea de hoy en el grupo correcto', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->setTime(23, 59),
        'completed'    => false,
    ]);

    $service = new TaskService();
    $grupos  = $service->tareasAgrupadas($user);

    expect($grupos['tareasHoy']->contains($tarea))->toBeTrue();
    expect($grupos['tareasFuturas']->contains($tarea))->toBeFalse();
    expect($grupos['tareasVencidas']->contains($tarea))->toBeFalse();
});

test('tareasAgrupadas devuelve tarea futura en el grupo correcto', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->addDays(2),
        'completed'    => false,
    ]);

    $service = new TaskService();
    $grupos  = $service->tareasAgrupadas($user);

    expect($grupos['tareasFuturas']->contains($tarea))->toBeTrue();
    expect($grupos['tareasHoy']->contains($tarea))->toBeFalse();
    expect($grupos['tareasVencidas']->contains($tarea))->toBeFalse();
});

test('tareasAgrupadas devuelve tarea vencida en el grupo correcto', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subDays(2),
        'completed'    => false,
    ]);

    $service = new TaskService();
    $grupos  = $service->tareasAgrupadas($user);

    expect($grupos['tareasVencidas']->contains($tarea))->toBeTrue();
    expect($grupos['tareasHoy']->contains($tarea))->toBeFalse();
    expect($grupos['tareasFuturas']->contains($tarea))->toBeFalse();
});

test('tareasAgrupadas no incluye tareas completadas', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subDays(1),
        'completed'    => true,
    ]);

    $service = new TaskService();
    $grupos  = $service->tareasAgrupadas($user);

    expect($grupos['tareasVencidas']->contains($tarea))->toBeFalse();
    expect($grupos['tareasHoy']->contains($tarea))->toBeFalse();
    expect($grupos['tareasFuturas']->contains($tarea))->toBeFalse();
});

// ── TaskService::completar ────────────────────────────────────────────────────

test('completar devuelve futura si la tarea aún no ha llegado', function () {
    $user  = User::factory()->create(['points' => 0, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->addDays(2),
        'completed'    => false,
    ]);

    $service    = new TaskService();
    $resultado  = $service->completar($tarea, $user);

    expect($resultado)->toBe('futura');
    expect($tarea->fresh()->completed)->toBeFalse();
});

test('completar devuelve completada y marca la tarea', function () {
    $user  = User::factory()->create(['points' => 0, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subHour(),
        'completed'    => false,
    ]);

    $service   = new TaskService();
    $resultado = $service->completar($tarea, $user);

    expect($resultado)->toBeIn(['completada', 'nivel']);
    expect($tarea->fresh()->completed)->toBeTrue();
});

test('completar otorga XP al usuario', function () {
    $user  = User::factory()->create(['points' => 0, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subHour(),
        'completed'    => false,
    ]);

    $service = new TaskService();
    $service->completar($tarea, $user);

    expect($user->fresh()->points)->toBe(TaskService::XP_TAREA);
});

test('completar devuelve nivel cuando el usuario sube de nivel', function () {
    // con 90 puntos, al sumar 10 llega a 100 y sube a nivel 2
    $user  = User::factory()->create(['points' => 90, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subHour(),
        'completed'    => false,
    ]);

    $service   = new TaskService();
    $resultado = $service->completar($tarea, $user);

    expect($resultado)->toBe('nivel');
    expect($user->fresh()->level)->toBe(2);
});

test('completar tarea de hoy es posible', function () {
    $user  = User::factory()->create(['points' => 0, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->setTime(23, 59),
        'completed'    => false,
    ]);

    $service   = new TaskService();
    $resultado = $service->completar($tarea, $user);

    expect($resultado)->not->toBe('futura');
    expect($tarea->fresh()->completed)->toBeTrue();
});

// ── TaskService::crear y actualizar ──────────────────────────────────────────

test('crear guarda la tarea en base de datos', function () {
    $user    = User::factory()->create();
    $service = new TaskService();

    $service->crear($user, [
        'title'        => 'Tarea de prueba',
        'description'  => 'Descripción',
        'scheduled_at' => now()->addDay(),
    ]);

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title'   => 'Tarea de prueba',
    ]);
});

test('actualizar modifica los datos de la tarea', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create([
        'user_id' => $user->id,
        'title'   => 'Título original',
    ]);

    $service = new TaskService();
    $service->actualizar($tarea, ['title' => 'Título actualizado']);

    expect($tarea->fresh()->title)->toBe('Título actualizado');
});

// ── TaskService::eliminar ─────────────────────────────────────────────────────

test('eliminar borra la tarea de base de datos', function () {
    $user  = User::factory()->create();
    $tarea = Task::factory()->create(['user_id' => $user->id]);

    $service = new TaskService();
    $service->eliminar($tarea);

    $this->assertDatabaseMissing('tasks', ['id' => $tarea->id]);
});

// ── HTTP: autorización ────────────────────────────────────────────────────────

test('un usuario no puede completar la tarea de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    $tarea = Task::factory()->create([
        'user_id'      => $usuario1->id,
        'scheduled_at' => now()->subHour(),
        'completed'    => false,
    ]);

    $this->actingAs($usuario2)
        ->patch(route('tareas.completar', $tarea))
        ->assertStatus(403);
});

test('un usuario no puede editar la tarea de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    $tarea = Task::factory()->create(['user_id' => $usuario1->id]);

    $this->actingAs($usuario2)
        ->get(route('tareas.edit', $tarea))
        ->assertStatus(403);
});

test('un usuario no puede eliminar la tarea de otro', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    $tarea = Task::factory()->create(['user_id' => $usuario1->id]);

    $this->actingAs($usuario2)
        ->delete(route('tareas.destroy', $tarea))
        ->assertStatus(403);
});

// ── HTTP: flujo normal ────────────────────────────────────────────────────────

test('usuario autenticado puede crear una tarea', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tareas.store'), [
            'title'        => 'Nueva misión',
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ])
        ->assertRedirect(route('tareas.index'));

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title'   => 'Nueva misión',
    ]);
});

test('no se puede crear una tarea con fecha en el pasado', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tareas.store'), [
            'title'        => 'Misión pasada',
            'scheduled_at' => now()->subDay()->format('Y-m-d H:i:s'),
        ])
        ->assertSessionHasErrors('scheduled_at');
});

test('no se puede crear una tarea sin título', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tareas.store'), [
            'title'        => '',
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ])
        ->assertSessionHasErrors('title');
});

test('completar una tarea ya completada no cambia su estado', function () {
    $user  = User::factory()->create(['points' => 0, 'level' => 1]);
    $tarea = Task::factory()->create([
        'user_id'      => $user->id,
        'scheduled_at' => now()->subHour(),
        'completed'    => true,
    ]);

    $this->actingAs($user)
        ->patch(route('tareas.completar', $tarea))
        ->assertRedirect(route('tareas.index'));

    // los puntos no cambian porque la tarea ya estaba completada
    expect($user->fresh()->points)->toBe(0);
});