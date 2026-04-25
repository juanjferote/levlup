<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\SuggestedHabit;
use App\Services\HabitService;
use Illuminate\Http\Request;
use App\Services\BadgeService;

class HabitController extends Controller
{
    public function __construct(
        private HabitService $habitService,
        private BadgeService $badgeService,
    ) {}

    /**
     * Lista de hábitos activos del usuario.
     */
    public function index()
    {
        $grupos = $this->habitService->habitosActivos(auth()->user());

        return view('habitos.index', $grupos);
    }

    /**
     * Formulario para crear un nuevo hábito.
     * Si viene de una sugerencia, pre-rellena los datos.
     */
    public function create(Request $request)
    {
        $sugerencia = null;

        if ($request->has('sugerencia')) {
            $sugerencia = SuggestedHabit::find($request->sugerencia);
        }

        return view('habitos.create', compact('sugerencia'));
    }

    /**
     * Valida y guarda un nuevo hábito.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'category'        => ['nullable', 'string', 'max:100'],
            'type'            => ['required', 'in:hacer,dejar'],
            'target_per_week' => ['required_if:type,hacer', 'nullable', 'integer', 'min:1', 'max:7'],
        ], [
            'title.required'                => 'El título es obligatorio.',
            'type.required'                 => 'Debes indicar el tipo de hábito.',
            'type.in'                       => 'El tipo debe ser "hacer" o "dejar".',
            'target_per_week.required_if'   => 'Indica cuántos días a la semana quieres cumplir este hábito.',
            'target_per_week.min'           => 'El mínimo es 1 día a la semana.',
            'target_per_week.max'           => 'El máximo es 7 días a la semana.',
        ]);

        $suggestedHabitId = $request->input('suggested_habit_id');

        $this->habitService->crear(auth()->user(), $datos, $suggestedHabitId);

        return redirect()->route('habitos.index')
            ->with('exito', '¡Hábito creado! Empieza a cumplirlo para ganar XP. ⭐');
    }

    /**
     * Formulario para editar un hábito existente.
     */
    public function edit(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        return view('habitos.edit', compact('habito'));
    }

    /**
     * Valida y actualiza un hábito existente.
     */
    public function update(Request $request, Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        $datos = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'category'        => ['nullable', 'string', 'max:100'],
            'target_per_week' => ['required_if:type,hacer', 'nullable', 'integer', 'min:1', 'max:7'],
        ], [
            'title.required'              => 'El título es obligatorio.',
            'target_per_week.required_if' => 'Indica cuántos días a la semana quieres cumplir este hábito.',
            'target_per_week.min'         => 'El mínimo es 1 día a la semana.',
            'target_per_week.max'         => 'El máximo es 7 días a la semana.',
        ]);

        $this->habitService->actualizar($habito, $datos);

        return redirect()->route('habitos.index')
            ->with('exito', 'Hábito actualizado correctamente. ✏️');
    }

    /**
     * Archiva un hábito en lugar de eliminarlo.
     */
    public function destroy(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        $this->habitService->archivar($habito);

        return redirect()->route('habitos.index')
            ->with('exito', 'Hábito archivado.');
    }

    /**
     * Registra el cumplimiento del hábito hoy y otorga XP.
     */
    public function registrar(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        $registrado = $this->habitService->registrarHoy($habito);

        if (!$registrado) {
            return redirect()->route('habitos.index')
                ->with('info', 'Ya has registrado este hábito hoy.');
        }

        $subioNivel      = $this->habitService->otorgarXp(auth()->user(), $habito);
        $insigniasNuevas = $this->badgeService->comprobarInsignias(auth()->user());

        $mensaje = $subioNivel
            ? '¡LEVEL UP! Has subido de nivel. 🎉 +' . HabitService::XP_HABITO . ' XP'
            : '¡Hábito registrado! +' . HabitService::XP_HABITO . ' XP ⭐';

        if ($insigniasNuevas->isNotEmpty()) {
            $mensaje .= ' · 🏆 ¡Nueva insignia desbloqueada: ' . $insigniasNuevas->first()->name . '!';
        }

        return redirect()->route('habitos.index')->with('exito', $mensaje);
    }
}
