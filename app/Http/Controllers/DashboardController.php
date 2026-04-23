<?php

namespace App\Http\Controllers;

use App\Services\NivelService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private NivelService $nivelService) {}

    public function index(): View
    {
        $user = auth()->user();

        return view('dashboard.index', [
            ...$this->nivelService->datosProgreso($user),
            'racha'      => 0, // se calculará al implementar hábitos
            'tareasHoy'  => 0, // se calculará al implementar tareas
            'habitosHoy' => 0, // se calculará al implementar hábitos
        ]);
    }
}
