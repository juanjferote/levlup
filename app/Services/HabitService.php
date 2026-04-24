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

        return [
            'habitosHacer' => $habitos->where('type', 'hacer'),
            'habitosDejar' => $habitos->where('type', 'dejar'),
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
}