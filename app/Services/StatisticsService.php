<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class StatisticsService
{
    public function __construct(private HabitService $habitService) {}

    /**
     * Devuelve todos los datos necesarios para la vista de estadísticas.
     */
    public function datosCompletos(User $user): array
    {
        return [
            ...$this->datosMisiones($user),
            ...$this->datosHabitos($user),
            ...$this->datosProgreso($user),
            ...$this->datosInsignias($user),
        ];
    }

    /**
     * Estadísticas de tareas.
     */
    private function datosMisiones(User $user): array
    {
        $totalCompletadas = $user->tasks()
            ->where('completed', true)
            ->count();

        $inicioSemana = now()->startOfWeek();
        $finSemana    = now()->endOfWeek();

        $completadasEstaSemana = $user->tasks()
            ->where('completed', true)
            ->whereBetween('updated_at', [$inicioSemana, $finSemana])
            ->count();

        return [
            'misionesTotalCompletadas'  => $totalCompletadas,
            'misionesEstaSemana'        => $completadasEstaSemana,
        ];
    }

    /**
     * Estadísticas de hábitos.
     */
    private function datosHabitos(User $user): array
    {
        $habitos = $user->habits()->where('active', true)->get();

        $totalHacer = $habitos->where('type', 'hacer')->count();
        $totalDejar = $habitos->where('type', 'dejar')->count();

        // tasa de cumplimiento semanal
        $tasaCumplimiento = $this->calcularTasaCumplimientoSemanal($user, $habitos);

        // mejor racha histórica entre todos los hábitos
        $mejorRacha = $this->calcularMejorRachaHistorica($user);

        // categoría más trabajada
        $categoriaMasTrabajada = $this->calcularCategoriaMasTrabajada($user);

        return [
            'habitosTotalHacer'      => $totalHacer,
            'habitosTotalDejar'      => $totalDejar,
            'habitosTasaSemanal'     => $tasaCumplimiento,
            'habitosMejorRacha'      => $mejorRacha,
            'habitosCategoriaStar'   => $categoriaMasTrabajada,
        ];
    }

    /**
     * Calcula el porcentaje de cumplimiento semanal.
     * Para hábitos de hacer: días cumplidos vs días objetivo esta semana.
     */
    private function calcularTasaCumplimientoSemanal(User $user, $habitos): int
    {
        $habitosHacer = $habitos->where('type', 'hacer');

        if ($habitosHacer->isEmpty()) {
            return 0;
        }

        $inicioSemana = now()->startOfWeek();
        $finSemana    = now()->endOfWeek();

        $totalObjetivo  = 0;
        $totalCumplido  = 0;

        foreach ($habitosHacer as $habito) {
            $totalObjetivo += $habito->target_per_week;
            $totalCumplido += $habito->logs()
                ->whereBetween('logged_date', [$inicioSemana, $finSemana])
                ->count();
        }

        if ($totalObjetivo === 0) return 0;

        return min(100, round(($totalCumplido / $totalObjetivo) * 100));
    }

    /**
     * Calcula la mejor racha histórica entre todos los hábitos del usuario.
     */
    private function calcularMejorRachaHistorica(User $user): int
    {
        $habitos = $user->habits()->get();
        $mejor   = 0;

        foreach ($habitos as $habito) {
            $racha = $habito->type === 'hacer'
                ? $this->habitService->calcularRachaHacer($habito)
                : $this->habitService->calcularRachaDejar($habito);

            if ($racha > $mejor) {
                $mejor = $racha;
            }
        }

        return $mejor;
    }

    /**
     * Devuelve la categoría con más logs registrados.
     */
    private function calcularCategoriaMasTrabajada(User $user): ?string
    {
        // contamos los logs agrupados por categoría del hábito
        $resultado = $user->habits()
            ->whereNotNull('category')
            ->withCount('logs')
            ->get()
            ->groupBy('category')
            ->map(fn($habitos) => $habitos->sum('logs_count'))
            ->sortDesc()
            ->keys()
            ->first();

        return $resultado ? ucfirst($resultado) : null;
    }

    /**
     * Datos de progreso del personaje.
     */
    private function datosProgreso(User $user): array
    {
        $tramoCosto      = 100 * $user->level;
        $faltan          = $user->pointsToNextLevel();
        $yaHechos        = $tramoCosto - $faltan;
        $porcentajeNivel = $user->level >= 10
            ? 100
            : round(($yaHechos / $tramoCosto) * 100);

        return [
            'progresoNivel'      => $user->level,
            'progresoPuntos'     => $user->points,
            'progresoFaltan'     => $faltan,
            'progresoPorcentaje' => $porcentajeNivel,
        ];
    }

    /**
     * Datos de insignias.
     */
    private function datosInsignias(User $user): array
    {
        $totalDisponibles   = Badge::count();
        $desbloqueadas      = $user->userBadges()->with('badge')->latest('unlocked_at')->get();
        $totalDesbloqueadas = $desbloqueadas->count();
        $porcentaje         = $totalDisponibles > 0
            ? round(($totalDesbloqueadas / $totalDisponibles) * 100)
            : 0;

        return [
            'insigniasTotalDisponibles'   => $totalDisponibles,
            'insigniasTotalDesbloqueadas' => $totalDesbloqueadas,
            'insigniasPorcentaje'         => $porcentaje,
            'insigniasRecientes'          => $desbloqueadas->take(3),
        ];
    }
}