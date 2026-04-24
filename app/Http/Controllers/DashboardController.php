<?php

namespace App\Http\Controllers;

use App\Services\NivelService;
use App\Services\TaskService;
use App\Services\HabitService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private NivelService $nivelService,
        private TaskService  $taskService,
        private HabitService $habitService,
    ) {}

    public function index(): View
    {
        $user    = auth()->user();
        $grupos  = $this->taskService->tareasAgrupadas($user);
        $habitos = $this->habitService->habitosActivos($user);

        return view('dashboard.index', [
            ...$this->nivelService->datosProgreso($user),
            'racha'          => 0,
            'tareasHoy'      => $grupos['tareasHoy']->count(),
            'habitosHoy'     => $habitos['habitosHacer']->count() + $habitos['habitosDejar']->count(),
            'tareasHoyLista' => $grupos['tareasHoy'],
            'habitosHacer'   => $habitos['habitosHacer'],
            'habitosDejar'   => $habitos['habitosDejar'],
        ]);
    }
}