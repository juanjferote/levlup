<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Collection;

class BadgeService
{
    /**
     * Comprueba todas las insignias que el usuario podría haber desbloqueado
     * y devuelve las que acaba de conseguir en esta llamada.
     * Es el único método público que deben llamar los controladores.
     */
    public function comprobarInsignias(User $user): Collection
    {
        $insigniasNuevas = collect();

        // obtenemos las insignias que el usuario ya tiene
        $yaDesbloqueadas = $user->userBadges()->pluck('badge_id')->toArray();

        // obtenemos todas las insignias pendientes
        $pendientes = Badge::whereNotIn('id', $yaDesbloqueadas)->get();

        foreach ($pendientes as $badge) {
            if ($this->cumpleCondicion($user, $badge)) {
                $this->desbloquear($user, $badge);
                $insigniasNuevas->push($badge);
            }
        }

        return $insigniasNuevas;
    }

    /**
     * Comprueba si el usuario cumple la condición de una insignia concreta.
     */
    private function cumpleCondicion(User $user, Badge $badge): bool
    {
        return match ($badge->condition_type) {
            'tasks_completed'  => $this->comprobarTareas($user, $badge->condition_value),
            'habit_hacer'      => $this->comprobarHabitHacer($user, $badge->condition_value),
            'habit_dejar'      => $this->comprobarHabitDejar($user, $badge->condition_value),
            'habit_category'   => $this->comprobarCategoria($user, $badge->category, $badge->condition_value),
            'diversity'        => $this->comprobarDiversidad($user, $badge->condition_value),
            'custom_interests' => $this->comprobarInteresesPersonalizados($user, $badge->condition_value),
            'level'            => $this->comprobarNivel($user, $badge->condition_value),
            'global_streak'    => $this->comprobarRachaGlobal($user, $badge->condition_value),
            default            => false,
        };
    }

    /**
     * Desbloquea una insignia para el usuario.
     */
    private function desbloquear(User $user, Badge $badge): void
    {
        $user->userBadges()->create([
            'badge_id'    => $badge->id,
            'unlocked_at' => now(),
        ]);
    }

    /**
     * Comprueba insignias de tareas completadas.
     */
    private function comprobarTareas(User $user, int $valor): bool
    {
        return $user->tasks()->where('completed', true)->count() >= $valor;
    }

    /**
     * Comprueba insignias de cumplimientos totales de hábitos de hacer.
     */
    private function comprobarHabitHacer(User $user, int $valor): bool
    {
        // contamos todos los logs de hábitos de tipo hacer del usuario
        return $user->habits()
            ->where('type', 'hacer')
            ->withCount('logs')
            ->get()
            ->sum('logs_count') >= $valor;
    }

    /**
     * Comprueba insignias de hábitos de dejar.
     * El usuario debe tener AL MENOS un hábito de dejar con esa racha.
     */
    private function comprobarHabitDejar(User $user, int $valor): bool
    {
        $habitosDejar = $user->habits()
            ->where('type', 'dejar')
            ->where('active', true)
            ->get();

        foreach ($habitosDejar as $habito) {
            $ultimoFallo = $habito->logs()->latest('logged_date')->first();

            $diasSinFallar = $ultimoFallo
                ? now()->diffInDays($ultimoFallo->logged_date)
                : now()->diffInDays($habito->created_at);

            if ($diasSinFallar >= $valor) {
                return true;
            }
        }

        return false;
    }

    /**
     * Comprueba insignias de categoría.
     * El usuario debe tener X semanas consecutivas cumpliendo en esa categoría.
     */
    private function comprobarCategoria(User $user, ?string $categoria, int $semanas): bool
    {
        if (!$categoria) return false;

        $habitos = $user->habits()
            ->where('category', $categoria)
            ->where('type', 'hacer')
            ->where('active', true)
            ->get();

        if ($habitos->isEmpty()) return false;

        $semanasConsecutivas = 0;
        $semanaOffset        = 0;

        while (true) {
            $inicioSemana = now()->subWeeks($semanaOffset)->startOfWeek();
            $finSemana    = now()->subWeeks($semanaOffset)->endOfWeek();

            $todoCumplido = $habitos->every(function ($habito) use ($inicioSemana, $finSemana) {
                return $habito->logs()
                    ->whereBetween('logged_date', [$inicioSemana, $finSemana])
                    ->count() >= $habito->target_per_week;
            });

            if (!$todoCumplido) break;

            $semanasConsecutivas++;
            $semanaOffset++;
        }

        return $semanasConsecutivas >= $semanas;
    }

    /**
     * Comprueba insignias de diversidad.
     * El usuario debe tener hábitos activos en X categorías distintas.
     */
    private function comprobarDiversidad(User $user, int $valor): bool
    {
        $categorias = $user->habits()
            ->where('active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return $categorias->count() >= $valor;
    }

    /**
     * Comprueba insignias de intereses personalizados.
     * Categorías del usuario que no están en las 12 predefinidas.
     */
    private function comprobarInteresesPersonalizados(User $user, int $valor): bool
    {
        $categoriasPredefinidas = [
            'deporte',
            'lectura',
            'meditacion',
            'nutricion',
            'productividad',
            'aprendizaje',
            'creatividad',
            'sueno',
            'social',
            'finanzas',
            'hogar',
            'naturaleza',
        ];

        $intereses = $user->interests ?? [];

        $personalizados = array_filter(
            $intereses,
            fn($interes) => !in_array($interes, $categoriasPredefinidas)
        );

        return count($personalizados) >= $valor;
    }

    /**
     * Comprueba insignias de nivel.
     */
    private function comprobarNivel(User $user, int $valor): bool
    {
        return $user->level >= $valor;
    }

    /**
     * Comprueba insignias de racha global.
     * El usuario debe tener actividad (tarea o hábito) X días consecutivos.
     */
    private function comprobarRachaGlobal(User $user, int $valor): bool
    {
        $racha        = 0;
        $diaOffset    = 0;

        while (true) {
            $dia = now()->subDays($diaOffset)->toDateString();

            // comprobamos si hubo alguna tarea completada o hábito registrado ese día
            $tareasEseDia = $user->tasks()
                ->where('completed', true)
                ->whereDate('updated_at', $dia)
                ->exists();

            $habitosEseDia = $user->habits()
                ->whereHas('logs', fn($q) => $q->whereDate('logged_date', $dia))
                ->exists();

            if (!$tareasEseDia && !$habitosEseDia) break;

            $racha++;
            $diaOffset++;
        }

        return $racha >= $valor;
    }

    /**
     * Devuelve todas las insignias agrupadas por tipo,
     * marcando cuáles tiene ya desbloqueadas el usuario.
     */
    public function insigniasParaVista(User $user): array
    {
        $todas            = Badge::all();
        $desbloqueadasIds = $user->userBadges()->pluck('badge_id')->toArray();

        // añadimos a cada insignia si está desbloqueada y el texto de condición
        $todas = $todas->map(function ($badge) use ($desbloqueadasIds) {
            $badge->desbloqueada    = in_array($badge->id, $desbloqueadasIds);
            $badge->texto_condicion = $this->textoCondicion($badge);
            return $badge;
        });

        return [
            'tareas'      => $todas->where('condition_type', 'tasks_completed')->values(),
            'hacer'       => $todas->where('condition_type', 'habit_hacer')->values(),
            'dejar'       => $todas->where('condition_type', 'habit_dejar')->values(),
            'categorias'  => $todas->where('condition_type', 'habit_category')->groupBy('category'),
            'diversidad'  => $todas->where('condition_type', 'diversity')->values(),
            'intereses'   => $todas->where('condition_type', 'custom_interests')->values(),
            'rachas'      => $todas->where('condition_type', 'global_streak')->values(),
            'niveles'     => $todas->where('condition_type', 'level')->values(),
        ];
    }

    /**
     * Devuelve el texto descriptivo de la condición de una insignia.
     */
    private function textoCondicion(Badge $badge): string
    {
        return match ($badge->condition_type) {
            'tasks_completed'  => "Completa {$badge->condition_value} tareas",
            'habit_hacer'      => "Registra {$badge->condition_value} cumplimientos de hábitos",
            'habit_dejar'      => "{$badge->condition_value} días sin fallar en un hábito de dejar",
            'habit_category'   => "{$badge->condition_value} semanas consecutivas cumpliendo en " . ucfirst($badge->category ?? ''),
            'diversity'        => "Hábitos activos en {$badge->condition_value} categorías distintas",
            'custom_interests' => "Añade {$badge->condition_value} intereses personalizados",
            'level'            => "Alcanza el nivel {$badge->condition_value}",
            'global_streak'    => "{$badge->condition_value} días consecutivos de actividad",
            default            => '',
        };
    }
}
