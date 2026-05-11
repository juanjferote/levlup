<?php

namespace App\Http\Controllers;

use App\Services\HabitService;
use Illuminate\View\View;
use App\Models\SuggestedHabit;
use App\Services\BadgeService;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SuggestionController extends Controller
{
    public function __construct(
        private HabitService $habitService,
        private BadgeService $badgeService,
    ) {}

    /**
     * Muestra las sugerencias de hábitos personalizadas para el usuario.
     */
    public function index(): View
    {
        $user        = auth()->user();
        $sugerencias = $this->habitService->sugerenciasParaUsuario($user);

        return view('sugerencias.index', compact('sugerencias'));
    }

    /**
     * Muestra el detalle de una sugerencia.
     */
    public function show(SuggestedHabit $sugerencia): View
    {
        return view('sugerencias.show', compact('sugerencia'));
    }

    /**
     * Crea un hábito directamente desde una sugerencia del sistema.
     */
    public function añadir(SuggestedHabit $sugerencia): RedirectResponse
    {
        $user = auth()->user();

        $yaExiste = $user->habits()
            ->where('title', $sugerencia->title)
            ->where('active', true)
            ->exists();

        if ($yaExiste) {
            return redirect()->route('sugerencias.index')
                ->with('info', 'Ya tienes este hábito activo.');
        }

        $this->habitService->crear($user, [
            'title'            => $sugerencia->title,
            'description'      => $sugerencia->description,
            'category'         => $sugerencia->category,
            'type'             => 'hacer',
            'target_per_week'  => $sugerencia->suggested_target_per_week,
            'duration_minutes' => $sugerencia->suggested_duration_minutes,
        ], $sugerencia->id);

        // comprobamos insignias de diversidad e intereses al añadir un hábito sugerido
        $insigniasNuevas = $this->badgeService->comprobarInsignias($user);

        $redirect = redirect()->route('habitos.index')
            ->with('exito', '¡Hábito añadido! Empieza a cumplirlo para ganar XP. ⭐');

        if ($insigniasNuevas->isNotEmpty()) {
            $redirect = $redirect->with('insignia_desbloqueada', [
                'nombre' => $insigniasNuevas->first()->name,
                'icono'  => $insigniasNuevas->first()->icon,
            ]);
        }

        return $redirect;
    }
}
