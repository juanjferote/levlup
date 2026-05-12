<?php

use App\Models\Badge;
use App\Models\Habit;
use App\Models\Task;
use App\Models\User;
use App\Services\BadgeService;
use App\Services\HabitService;

// ── BadgeService::comprobarInsignias — tareas ─────────────────────────────────

test('se desbloquea insignia al completar la primera tarea', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'tasks_completed',
        'condition_value' => 1,
    ]);

    Task::factory()->create([
        'user_id'   => $user->id,
        'completed' => true,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
    expect($user->userBadges()->where('badge_id', $badge->id)->exists())->toBeTrue();
});

test('no se desbloquea insignia de tareas si no se ha completado ninguna', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'tasks_completed',
        'condition_value' => 1,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});

test('no se desbloquea la misma insignia dos veces', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'tasks_completed',
        'condition_value' => 1,
    ]);

    Task::factory()->create([
        'user_id'   => $user->id,
        'completed' => true,
    ]);

    $service = new BadgeService(new HabitService());
    $service->comprobarInsignias($user);
    $segundaVez = $service->comprobarInsignias($user);

    expect($segundaVez->contains('id', $badge->id))->toBeFalse();
    expect($user->userBadges()->where('badge_id', $badge->id)->count())->toBe(1);
});

// ── BadgeService::comprobarInsignias — nivel ──────────────────────────────────

test('se desbloquea insignia al alcanzar el nivel requerido', function () {
    $user  = User::factory()->create(['level' => 3]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'level',
        'condition_value' => 3,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('no se desbloquea insignia de nivel si no se ha alcanzado', function () {
    $user  = User::factory()->create(['level' => 2]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'level',
        'condition_value' => 3,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});

// ── BadgeService::comprobarInsignias — habit_hacer ────────────────────────────

test('se desbloquea insignia de hacer al registrar el primer cumplimiento', function () {
    $user   = User::factory()->create();
    $habito = Habit::factory()->hacer()->create(['user_id' => $user->id]);
    $badge  = Badge::factory()->create([
        'condition_type'  => 'habit_hacer',
        'condition_value' => 1,
    ]);

    $habito->logs()->create(['logged_date' => now()->toDateString()]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('insignia habit_hacer valor 1 se desbloquea si el usuario tiene un habito dejar activo', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'habit_hacer',
        'condition_value' => 1,
    ]);

    Habit::factory()->dejar()->create(['user_id' => $user->id]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

// ── BadgeService::comprobarInsignias — habit_dejar ────────────────────────────

test('se desbloquea insignia de dejar si la racha supera el valor requerido', function () {
    $user   = User::factory()->create();
    Habit::factory()->dejar()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subDays(8),
    ]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'habit_dejar',
        'condition_value' => 7,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('no se desbloquea insignia de dejar si la racha no supera el valor', function () {
    $user   = User::factory()->create();
    Habit::factory()->dejar()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subDays(3),
    ]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'habit_dejar',
        'condition_value' => 7,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});

// ── BadgeService::comprobarInsignias — diversidad ─────────────────────────────

test('se desbloquea insignia de diversidad con habitos en categorias distintas', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'diversity',
        'condition_value' => 2,
    ]);

    Habit::factory()->hacer()->create(['user_id' => $user->id, 'category' => 'deporte']);
    Habit::factory()->hacer()->create(['user_id' => $user->id, 'category' => 'lectura']);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('no se desbloquea insignia de diversidad si todos los habitos son de la misma categoria', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'diversity',
        'condition_value' => 2,
    ]);

    Habit::factory()->hacer()->create(['user_id' => $user->id, 'category' => 'deporte']);
    Habit::factory()->hacer()->create(['user_id' => $user->id, 'category' => 'deporte']);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});

// ── BadgeService::comprobarInsignias — intereses personalizados ───────────────

test('se desbloquea insignia de intereses personalizados', function () {
    $user  = User::factory()->create(['interests' => ['programacion', 'musica', 'cocina']]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'custom_interests',
        'condition_value' => 3,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('categorias predefinidas no cuentan como intereses personalizados', function () {
    $user  = User::factory()->create(['interests' => ['deporte', 'lectura', 'meditacion']]);
    $badge = Badge::factory()->create([
        'condition_type'  => 'custom_interests',
        'condition_value' => 1,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});

// ── BadgeService::comprobarInsignias — special_task ───────────────────────────

test('se desbloquea insignia especial al completar la tarea de defensa', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'special_task',
        'condition_value' => 999,
    ]);

    Task::factory()->create([
        'user_id'   => $user->id,
        'title'     => 'Defensa proyecto final',
        'completed' => true,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeTrue();
});

test('no se desbloquea insignia especial si la tarea no esta completada', function () {
    $user  = User::factory()->create();
    $badge = Badge::factory()->create([
        'condition_type'  => 'special_task',
        'condition_value' => 999,
    ]);

    Task::factory()->create([
        'user_id'   => $user->id,
        'title'     => 'Defensa proyecto final',
        'completed' => false,
    ]);

    $service = new BadgeService(new HabitService());
    $nuevas  = $service->comprobarInsignias($user);

    expect($nuevas->contains('id', $badge->id))->toBeFalse();
});