<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuario ──
        $user = User::create([
            'name'        => 'Juanjo',
            'email'       => 'juanjo@mail.com',
            'password'    => Hash::make('password'),
            'avatar_seed' => 'warrior',
            'interests'   => ['deporte', 'lectura', 'productividad', 'meditacion', 'nutricion', 'creatividad', 'fotografía', 'cocina'],
            'points'      => 2600,
            'level'       => 7,
        ]);

        // ── Tareas completadas ──
        $tareasCompletadas = [
            'Revisar correos', 'Reunión de equipo', 'Entregar informe',
            'Llamar al banco', 'Comprar material', 'Actualizar CV',
            'Revisar presupuesto', 'Enviar propuesta', 'Leer documentación',
            'Configurar entorno', 'Hacer backup', 'Revisar pull requests',
            'Planificar sprint', 'Escribir tests', 'Actualizar dependencias',
            'Revisar métricas', 'Preparar presentación', 'Documentar API',
            'Revisar feedback', 'Cerrar tickets pendientes',
        ];

        foreach ($tareasCompletadas as $titulo) {
            Task::create([
                'user_id'      => $user->id,
                'title'        => $titulo,
                'completed'    => true,
                'scheduled_at' => now()->subDays(rand(1, 30))->setHour(rand(9, 18))->setMinute(0),
            ]);
        }

        // ── Tareas para hoy ──
        Task::create(['user_id' => $user->id, 'title' => 'Revisar estadísticas del proyecto',  'completed' => false, 'scheduled_at' => now()->setHour(10)->setMinute(0)]);
        Task::create(['user_id' => $user->id, 'title' => 'Preparar defensa del proyecto',      'completed' => false, 'scheduled_at' => now()->setHour(12)->setMinute(30)]);
        Task::create(['user_id' => $user->id, 'title' => 'Ensayo final de la presentación',    'completed' => false, 'scheduled_at' => now()->setHour(17)->setMinute(0)]);

        // ── Tarea especial para la defensa ──
        Task::create(['user_id' => $user->id, 'title' => 'Defensa proyecto final', 'completed' => false, 'scheduled_at' => now()->addDay()->setHour(10)->setMinute(0)]);

        // ── Tareas futuras ──
        Task::create(['user_id' => $user->id, 'title' => 'Reunión de seguimiento',      'completed' => false, 'scheduled_at' => now()->addDays(2)->setHour(11)->setMinute(0)]);
        Task::create(['user_id' => $user->id, 'title' => 'Entrega documentación final', 'completed' => false, 'scheduled_at' => now()->addDays(4)->setHour(9)->setMinute(0)]);
        Task::create(['user_id' => $user->id, 'title' => 'Revisión con el tutor',       'completed' => false, 'scheduled_at' => now()->addDays(7)->setHour(16)->setMinute(0)]);

        // ── Tareas vencidas ──
        Task::create(['user_id' => $user->id, 'title' => 'Subir cambios al repositorio',  'completed' => false, 'scheduled_at' => now()->subDays(2)->setHour(9)->setMinute(0)]);
        Task::create(['user_id' => $user->id, 'title' => 'Revisar comentarios del tutor', 'completed' => false, 'scheduled_at' => now()->subDays(1)->setHour(15)->setMinute(0)]);

        // ── Hábito: Correr — objetivo semanal YA cumplido + racha global 10 días ──
        $correr = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Correr',
            'description'     => 'Salir a correr al menos 30 minutos.',
            'type'            => 'hacer',
            'category'        => 'deporte',
            'target_per_week' => 3,
            'active'          => true,
        ]);
        // 8 semanas de racha
        for ($semana = 8; $semana >= 1; $semana--) {
            for ($dia = 0; $dia < 3; $dia++) {
                $correr->logs()->create([
                    'logged_date' => now()->subWeeks($semana)->startOfWeek()->addDays($dia)->toDateString(),
                ]);
            }
        }
        // logs de los últimos 10 días consecutivos para racha global
        for ($dia = 9; $dia >= 0; $dia--) {
            $correr->logs()->updateOrCreate(
                ['logged_date' => now()->subDays($dia)->toDateString()],
                ['habit_id'    => $correr->id]
            );
        }

        // ── Hábito: Leer — registrado hoy, objetivo no cumplido ──
        $leer = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Leer 30 minutos',
            'description'     => 'Leer al menos 30 minutos al día.',
            'type'            => 'hacer',
            'category'        => 'lectura',
            'target_per_week' => 2,
            'active'          => true,
        ]);
        for ($semana = 6; $semana >= 1; $semana--) {
            for ($dia = 0; $dia < 2; $dia++) {
                $leer->logs()->create([
                    'logged_date' => now()->subWeeks($semana)->startOfWeek()->addDays($dia)->toDateString(),
                ]);
            }
        }
        $leer->logs()->create(['logged_date' => now()->toDateString()]);

        // ── Hábito: Meditar — pendiente ──
        $meditar = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Meditar',
            'description'     => 'Sesión de meditación de 10 minutos.',
            'type'            => 'hacer',
            'category'        => 'meditacion',
            'target_per_week' => 5,
            'active'          => true,
        ]);
        for ($semana = 4; $semana >= 1; $semana--) {
            for ($dia = 0; $dia < 5; $dia++) {
                $meditar->logs()->create([
                    'logged_date' => now()->subWeeks($semana)->startOfWeek()->addDays($dia)->toDateString(),
                ]);
            }
        }

        // ── Hábito: Planificar el día — pendiente ──
        $planificar = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Planificar el día',
            'description'     => 'Dedicar 10 minutos a planificar las tareas del día.',
            'type'            => 'hacer',
            'category'        => 'productividad',
            'target_per_week' => 1,
            'active'          => true,
        ]);
        for ($semana = 3; $semana >= 1; $semana--) {
            $planificar->logs()->create([
                'logged_date' => now()->subWeeks($semana)->startOfWeek()->toDateString(),
            ]);
        }

        // ── Hábito: Beber agua — pendiente ──
        $agua = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Beber 2 litros de agua',
            'description'     => 'Mantener una hidratación adecuada durante el día.',
            'type'            => 'hacer',
            'category'        => 'nutricion',
            'target_per_week' => 7,
            'active'          => true,
        ]);
        for ($dia = 0; $dia < 7; $dia++) {
            $agua->logs()->create([
                'logged_date' => now()->subWeeks(1)->startOfWeek()->addDays($dia)->toDateString(),
            ]);
        }

        // ── Hábito: Estudiar inglés — pendiente ──
        $ingles = Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Estudiar inglés',
            'description'     => 'Practicar inglés 20 minutos al día.',
            'type'            => 'hacer',
            'category'        => 'aprendizaje',
            'target_per_week' => 2,
            'active'          => true,
        ]);
        for ($semana = 2; $semana >= 1; $semana--) {
            for ($dia = 0; $dia < 2; $dia++) {
                $ingles->logs()->create([
                    'logged_date' => now()->subWeeks($semana)->startOfWeek()->addDays($dia)->toDateString(),
                ]);
            }
        }

        // ── Hábito archivado ──
        Habit::create([
            'user_id'         => $user->id,
            'title'           => 'Hacer yoga',
            'description'     => 'Sesión de yoga de 20 minutos.',
            'type'            => 'hacer',
            'category'        => 'deporte',
            'target_per_week' => 3,
            'active'          => false,
        ]);

        // ── Hábitos de dejar con rachas variadas ──

        // 45 días — para fallar en la defensa
        $movil = new Habit();
        $movil->user_id     = $user->id;
        $movil->title       = 'Dejar el móvil por la noche';
        $movil->description = 'No usar el móvil después de las 22:00.';
        $movil->type        = 'dejar';
        $movil->category    = '';
        $movil->active      = true;
        $movil->timestamps  = false;
        $movil->created_at  = now()->subDays(45);
        $movil->updated_at  = now()->subDays(45);
        $movil->save();

        // 30 días
        $cafe = new Habit();
        $cafe->user_id     = $user->id;
        $cafe->title       = 'Dejar el café';
        $cafe->description = 'Eliminar el consumo de cafeína.';
        $cafe->type        = 'dejar';
        $cafe->category    = '';
        $cafe->active      = true;
        $cafe->timestamps  = false;
        $cafe->created_at  = now()->subDays(30);
        $cafe->updated_at  = now()->subDays(30);
        $cafe->save();

        // 15 días
        $alcohol = new Habit();
        $alcohol->user_id     = $user->id;
        $alcohol->title       = 'Dejar el alcohol';
        $alcohol->description = 'No consumir ninguna bebida alcohólica.';
        $alcohol->type        = 'dejar';
        $alcohol->category    = '';
        $alcohol->active      = true;
        $alcohol->timestamps  = false;
        $alcohol->created_at  = now()->subDays(15);
        $alcohol->updated_at  = now()->subDays(15);
        $alcohol->save();

        // 7 días
        $instagram = new Habit();
        $instagram->user_id     = $user->id;
        $instagram->title       = 'Dejar el instagram';
        $instagram->description = 'No utilizar el instagram.';
        $instagram->type        = 'dejar';
        $instagram->category    = '';
        $instagram->active      = true;
        $instagram->timestamps  = false;
        $instagram->created_at  = now()->subDays(7);
        $instagram->updated_at  = now()->subDays(7);
        $instagram->save();

        // 3 días
        $azucar = new Habit();
        $azucar->user_id     = $user->id;
        $azucar->title       = 'Dejar el azúcar';
        $azucar->description = 'Eliminar el azúcar refinado de la dieta.';
        $azucar->type        = 'dejar';
        $azucar->category    = '';
        $azucar->active      = true;
        $azucar->timestamps  = false;
        $azucar->created_at  = now()->subDays(3);
        $azucar->updated_at  = now()->subDays(3);
        $azucar->save();
    }
}