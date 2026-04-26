<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __construct(private StatisticsService $statisticsService) {}

    /**
     * Muestra la página de estadísticas del usuario.
     */
    public function index(): View
    {
        $datos = $this->statisticsService->datosCompletos(auth()->user());

        return view('estadisticas.index', $datos);
    }
}