<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\User;
use App\Models\SuggestedHabit;

class HabitService
{
    // XP base por registrar un cumplimiento de hábito
    const XP_HABITO = 20;

    // XP bonus por racha semanal
    const XP_BONUS_RACHA_7  = 10;
    const XP_BONUS_RACHA_30 = 25;

    /**
     * Devuelve los hábitos activos del usuario separados por tipo,
     * con la información de si ya se han registrado hoy.
     */
    public function habitosActivos(User $user): array
    {
        $habitos = $user->habits()->where('active', true)->get();
        $hoy     = now()->toDateString();

        $completadosHoy = $habitos->filter(function ($habito) use ($hoy) {
            return $habito->logs()->whereDate('logged_date', $hoy)->exists();
        });

        $sinCompletar = $habitos->filter(function ($habito) use ($hoy) {
            return !$habito->logs()->whereDate('logged_date', $hoy)->exists();
        });

        return [
            'habitosHacer'      => $sinCompletar->where('type', 'hacer'),
            'habitosDejar'      => $sinCompletar->where('type', 'dejar'),
            'habitosCompletados' => $completadosHoy,
        ];
    }

    /**
     * Crea un nuevo hábito asociado al usuario.
     * Si viene de una sugerencia, añade los datos correspondientes.
     */
    public function crear(User $user, array $datos, ?int $suggestedHabitId = null): Habit
    {
        if ($suggestedHabitId) {
            $sugerencia = SuggestedHabit::findOrFail($suggestedHabitId);
            $datos['suggested_by_system'] = true;
            $datos['difficulty_level']    = $sugerencia->difficulty_level;
            $datos['category']            = $sugerencia->category;
        }

        return $user->habits()->create($datos);
    }

    /**
     * Actualiza los datos de un hábito existente.
     */
    public function actualizar(Habit $habit, array $datos): void
    {
        $habit->update($datos);
    }

    /**
     * Archiva un hábito (no lo elimina, lo desactiva).
     */
    public function archivar(Habit $habit): void
    {
        $habit->update(['active' => false]);
    }

    /**
     * Elimina un hábito y todos sus registros.
     */
    public function eliminar(Habit $habit): void
    {
        $habit->delete();
    }

    /**
     * Registra el cumplimiento de un hábito hoy.
     * Devuelve false si ya estaba registrado hoy.
     */
    public function registrarHoy(Habit $habit): bool
    {
        $hoy = now()->toDateString();

        // comprobamos si ya está registrado hoy
        $yaRegistrado = $habit->logs()->whereDate('logged_date', $hoy)->exists();

        if ($yaRegistrado) {
            return false;
        }

        $habit->logs()->create(['logged_date' => $hoy]);

        return true;
    }

    /**
     * Calcula la racha actual de un hábito de tipo "hacer".
     * La racha es el número de semanas consecutivas en las que
     * el usuario ha cumplido su objetivo semanal.
     */
    public function calcularRachaHacer(Habit $habit): int
    {
        $racha        = 0;
        $semanaOffset = 0;

        while (true) {
            // calculamos el inicio y fin de la semana a comprobar
            $inicioSemana = now()->subWeeks($semanaOffset)->startOfWeek();
            $finSemana    = now()->subWeeks($semanaOffset)->endOfWeek();

            // contamos los logs de esa semana
            $logsEnSemana = $habit->logs()
                ->whereBetween('logged_date', [$inicioSemana, $finSemana])
                ->count();

            // si no se cumplió el objetivo, rompemos la racha
            if ($logsEnSemana < $habit->target_per_week) {
                break;
            }

            $racha++;
            $semanaOffset++;
        }

        return $racha;
    }

    /**
     * Calcula la racha actual de un hábito de tipo "dejar".
     * La racha son los días consecutivos sin ningún log de fallo.
     * El log en hábitos de "dejar" representa un fallo, no un éxito.
     */
    public function calcularRachaDejar(Habit $habit): int
    {
        // buscamos el último fallo registrado
        $ultimoFallo = $habit->logs()->latest('logged_date')->first();

        if (!$ultimoFallo) {
            // nunca ha fallado, la racha es desde que creó el hábito
            return now()->diffInDays($habit->created_at);
        }

        return now()->diffInDays($ultimoFallo->logged_date);
    }

    /**
     * Otorga XP al usuario por registrar un hábito.
     * Aplica bonus si se alcanzan hitos de racha.
     * Devuelve true si el usuario ha subido de nivel.
     */
    public function otorgarXp(User $user, Habit $habit): bool
    {
        $xp    = self::XP_HABITO;
        $racha = $habit->type === 'hacer'
            ? $this->calcularRachaHacer($habit)
            : $this->calcularRachaDejar($habit);

        // bonus por hitos de racha
        if ($racha === 7) {
            $xp += self::XP_BONUS_RACHA_7;
        } elseif ($racha === 30) {
            $xp += self::XP_BONUS_RACHA_30;
        }

        return $user->addPoints($xp);
    }
    /**
     * Calcula las semanas consecutivas que el usuario ha cumplido
     * su objetivo semanal en hábitos de una categoría concreta.
     * Se usa para determinar la dificultad de las sugerencias.
     */
    public function semanasConsecutivasEnCategoria(User $user, string $category): int
    {
        // obtenemos los hábitos activos del usuario en esa categoría
        $habitos = $user->habits()
            ->where('category', $category)
            ->where('type', 'hacer')
            ->where('active', true)
            ->get();

        if ($habitos->isEmpty()) {
            return 0;
        }

        $semanas      = 0;
        $semanaOffset = 0;

        while (true) {
            $inicioSemana = now()->subWeeks($semanaOffset)->startOfWeek();
            $finSemana    = now()->subWeeks($semanaOffset)->endOfWeek();

            // comprobamos si TODOS los hábitos de la categoría cumplieron su objetivo esa semana
            $todoCumplido = $habitos->every(function ($habito) use ($inicioSemana, $finSemana) {
                $logs = $habito->logs()
                    ->whereBetween('logged_date', [$inicioSemana, $finSemana])
                    ->count();

                return $logs >= $habito->target_per_week;
            });

            if (!$todoCumplido) {
                break;
            }

            $semanas++;
            $semanaOffset++;
        }

        return $semanas;
    }

    /**
     * Determina la dificultad de sugerencias para el usuario en una categoría.
     * Basado en semanas consecutivas cumpliendo el objetivo en esa categoría.
     */
    public function dificultadSugerida(User $user, string $category): int
    {
        $semanas = $this->semanasConsecutivasEnCategoria($user, $category);

        if ($semanas >= 17) return 5;
        if ($semanas >= 13) return 4;
        if ($semanas >= 9)  return 3;
        if ($semanas >= 5)  return 2;

        return 1;
    }

    /**
     * Devuelve las sugerencias de hábitos para el usuario
     * filtradas por sus intereses y ajustadas a su nivel en cada categoría.
     */
    public function sugerenciasParaUsuario(User $user): array
    {
        $intereses   = $user->interests ?? [];
        $sugerencias = [];

        foreach ($intereses as $categoria) {
            $dificultad = $this->dificultadSugerida($user, $categoria);

            // obtenemos hábitos sugeridos de esa categoría y dificultad
            // excluimos los que el usuario ya tiene activos
            $habitosUsuario = $user->habits()
                ->where('category', $categoria)
                ->where('suggested_by_system', true)
                ->pluck('title')
                ->toArray();

            $sugerencias[$categoria] = SuggestedHabit::where('category', $categoria)
                ->where('difficulty_level', $dificultad)
                ->whereNotIn('title', $habitosUsuario)
                ->get();
        }

        return $sugerencias;
    }

    /**
     * Devuelve los logs de un hábito en la semana actual.
     */
    public function logsEstaSemana(Habit $habit): int
    {
        return $habit->logs()
            ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    /**
     * Añade el progreso semanal a una colección de hábitos de hacer.
     * Evita hacer consultas en la vista.
     */
    public function conProgresoSemanal($habitos): \Illuminate\Support\Collection
    {
        $inicioSemana = now()->startOfWeek();
        $finSemana    = now()->endOfWeek();

        return collect($habitos)->map(function ($habito) use ($inicioSemana, $finSemana) {
            $habito->logs_esta_semana = $habito->type === 'hacer'
                ? $habito->logs()->whereBetween('logged_date', [$inicioSemana, $finSemana])->count()
                : null;
            return $habito;
        });
    }
}
