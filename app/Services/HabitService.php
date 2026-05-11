<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\User;
use App\Models\SuggestedHabit;

class HabitService
{
    // XP base por registrar un cumplimiento de hábito de hacer
    const XP_HABITO = 20;

    // XP bonus por racha media (cada 4 semanas en hacer, cada 7 días en dejar)
    const XP_BONUS_RACHA_MEDIO = 10;

    // XP bonus por racha larga (cada 8 semanas en hacer, cada 30 días en dejar)
    const XP_BONUS_RACHA_LARGO = 25;

    /**
     * Devuelve los hábitos activos del usuario separados por tipo,
     * con la información de si ya se han registrado hoy.
     */
    public function habitosActivos(User $user): array
    {
        $habitos      = $user->habits()->where('active', true)->get();
        $hoy          = now()->toDateString();
        $inicioSemana = now()->startOfWeek();
        $finSemana    = now()->endOfWeek();

        $pendientes = $habitos->filter(function ($habito) use ($hoy, $inicioSemana, $finSemana) {
            if ($habito->type === 'hacer') {
                $logsHoy        = $habito->logs()->whereDate('logged_date', $hoy)->exists();
                $logsEstaSemana = $habito->logs()->whereBetween('logged_date', [$inicioSemana, $finSemana])->count();
                return !$logsHoy && $logsEstaSemana < $habito->target_per_week;
            }
            return false;
        });

        $registradosHoy = $habitos->filter(function ($habito) use ($hoy, $inicioSemana, $finSemana) {
            if ($habito->type !== 'hacer') return false;
            $logsHoy        = $habito->logs()->whereDate('logged_date', $hoy)->exists();
            $logsEstaSemana = $habito->logs()->whereBetween('logged_date', [$inicioSemana, $finSemana])->count();
            return $logsHoy && $logsEstaSemana < $habito->target_per_week;
        });

        $objetivoCumplido = $habitos->filter(function ($habito) use ($inicioSemana, $finSemana) {
            if ($habito->type !== 'hacer') return false;
            $logsEstaSemana = $habito->logs()->whereBetween('logged_date', [$inicioSemana, $finSemana])->count();
            return $logsEstaSemana >= $habito->target_per_week;
        });

        // hábitos de dejar: todos los activos, independientemente de si han fallado hoy
        $habitosDejar = $habitos->filter(fn($h) => $h->type === 'dejar');

        return [
            'habitosHacer'       => $pendientes,
            'habitosDejar'       => $habitosDejar,
            'habitosRegistrados' => $registradosHoy,
            'habitosCompletados' => $objetivoCumplido,
        ];
    }

    /**
     * Devuelve los hábitos archivados del usuario.
     */
    public function habitosArchivados(User $user): \Illuminate\Support\Collection
    {
        return $user->habits()->where('active', false)->get();
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
     * En hábitos de hacer representa un éxito.
     * En hábitos de dejar representa un fallo que reinicia la racha.
     * Devuelve false si ya estaba registrado hoy.
     */
    public function registrarHoy(Habit $habit): bool
    {
        $hoy = now()->toDateString();

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
            $inicioSemana = now()->subWeeks($semanaOffset)->startOfWeek();
            $finSemana    = now()->subWeeks($semanaOffset)->endOfWeek();

            $logsEnSemana = $habit->logs()
                ->whereBetween('logged_date', [$inicioSemana, $finSemana])
                ->count();

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
     * La racha son los días transcurridos desde el último fallo registrado.
     * El log en hábitos de "dejar" representa un fallo, no un éxito.
     * Si nunca ha fallado, la racha se calcula desde la fecha de creación.
     */
    public function calcularRachaDejar(Habit $habit): int
    {
        $ultimoFallo = $habit->logs()->latest('logged_date')->first();

        if (!$ultimoFallo) {
            // nunca ha fallado, la racha es desde que creó el hábito
            return $habit->created_at->copy()->startOfDay()->diffInDays(now());
        }

        return $ultimoFallo->logged_date->diffInDays(now());
    }

    /**
     * Otorga XP al usuario por registrar un hábito de hacer.
     * Aplica bonus si se alcanzan hitos de racha semanal.
     * - Bonus medio: cada 4 semanas consecutivas cumpliendo el objetivo
     * - Bonus largo: cada 8 semanas (se acumula al anterior)
     * Los hábitos de dejar no otorgan XP al registrar un fallo.
     * Devuelve true si el usuario ha subido de nivel.
     */
    public function otorgarXp(User $user, Habit $habit): bool
    {
        // los hábitos de dejar no otorgan XP al registrar un fallo
        if ($habit->type === 'dejar') {
            return false;
        }

        $xp    = self::XP_HABITO;
        $racha = $this->calcularRachaHacer($habit);

        // bonus cada 4 semanas consecutivas cumpliendo el objetivo
        if ($racha > 0 && $racha % 4 === 0) {
            $xp += self::XP_BONUS_RACHA_MEDIO;
        }
        // bonus extra cada 8 semanas (se acumula al anterior)
        if ($racha > 0 && $racha % 8 === 0) {
            $xp += self::XP_BONUS_RACHA_LARGO;
        }

        return $user->addPoints($xp);
    }

    /**
     * Comprueba los hábitos de dejar del usuario y otorga XP
     * si se han alcanzado hitos de racha nuevos (7 o 30 días).
     * Se llama desde el DashboardController al cargar el dashboard.
     * Devuelve true si el usuario ha subido de nivel.
     */
    public function otorgarXpRachasDejar(User $user): bool
    {
        $subioNivel   = false;
        $habitosDejar = $user->habits()
            ->where('type', 'dejar')
            ->where('active', true)
            ->get();

        foreach ($habitosDejar as $habito) {
            $racha = $this->calcularRachaDejar($habito);
            $xp    = 0;

            // bonus cada 7 días sin fallar
            if ($racha > 0 && $racha % 7 === 0) {
                $xp += self::XP_BONUS_RACHA_MEDIO;
            }
            // bonus extra cada 30 días (se acumula al anterior)
            if ($racha > 0 && $racha % 30 === 0) {
                $xp += self::XP_BONUS_RACHA_LARGO;
            }

            if ($xp > 0) {
                $subio = $user->addPoints($xp);
                if ($subio) {
                    $subioNivel = true;
                }
            }
        }

        return $subioNivel;
    }

    /**
     * Calcula la racha global del usuario: días consecutivos
     * con alguna actividad (tarea completada o hábito de hacer registrado).
     * Los hábitos de dejar no cuentan al ser pasivos.
     */
    public function calcularRachaGlobal(User $user): int
    {
        $racha     = 0;
        $diaOffset = 0;

        while (true) {
            $dia = now()->subDays($diaOffset)->toDateString();

            $tareasEseDia = $user->tasks()
                ->where('completed', true)
                ->whereDate('updated_at', $dia)
                ->exists();

            $habitosEseDia = $user->habits()
                ->where('type', 'hacer')
                ->whereHas('logs', fn($q) => $q->whereDate('logged_date', $dia))
                ->exists();

            if (!$tareasEseDia && !$habitosEseDia) break;

            $racha++;
            $diaOffset++;
        }

        return $racha;
    }

    /**
     * Calcula las semanas consecutivas que el usuario ha cumplido
     * su objetivo semanal en hábitos de una categoría concreta.
     */
    public function semanasConsecutivasEnCategoria(User $user, string $category): int
    {
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

    /**
     * Añade la racha calculada a una colección de hábitos de dejar.
     * Evita hacer consultas en la vista.
     */
    public function conRachaDejar($habitos): \Illuminate\Support\Collection
    {
        return collect($habitos)->map(function ($habito) {
            $habito->racha_dias = max(0, $this->calcularRachaDejar($habito));
            return $habito;
        });
    }

    /**
     * Reactiva un hábito archivado.
     */
    public function recuperar(Habit $habit): void
    {
        $habit->update(['active' => true]);
    }
}
