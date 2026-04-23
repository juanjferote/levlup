<?php

namespace App\Http\Controllers;

use App\Services\NivelService;
use App\Services\TaskService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private NivelService $nivelService,
        private TaskService $taskService,
    ) {}

    public function index(): View
    {
        $user   = auth()->user();
        $grupos = $this->taskService->tareasAgrupadas($user);

        return view('dashboard.index', [
            ...$this->nivelService->datosProgreso($user),
            'racha'      => 0, // se calculará al implementar hábitos
            'tareasHoy'  => $grupos['tareasHoy']->count(),
            'habitosHoy' => 0, // se calculará al implementar hábitos
        ]);
    }
}