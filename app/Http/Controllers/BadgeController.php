<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function __construct(private BadgeService $badgeService) {}

    /**
     * Muestra todas las insignias agrupadas por tipo.
     */
    public function index(): View
    {
        $grupos = $this->badgeService->insigniasParaVista(auth()->user());

        return view('insignias.index', $grupos);
    }
}
