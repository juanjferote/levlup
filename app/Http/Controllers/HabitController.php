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

        return view('habitos.index', [
            'habitosHacer'       => $this->habitService->conProgresoSemanal($grupos['habitosHacer']),
            'habitosDejar'       => $this->habitService->conRachaDejar($grupos['habitosDejar']),
            'habitosRegistrados' => $this->habitService->conProgresoSemanal($grupos['habitosRegistrados']),
            'habitosCompletados' => $this->habitService->conProgresoSemanal($grupos['habitosCompletados']),
            'habitosArchivados'  => $this->habitService->habitosArchivados(auth()->user()),
            'habitosFallados'    => $this->habitService->habitosFalladosHoy(auth()->user()),
        ]);
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

        $categoriasPredefinidas = ['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad', 'aprendizaje', 'creatividad', 'descanso', 'social', 'finanzas', 'hogar', 'naturaleza'];

        $interesesPersonalizados = array_filter(
            auth()->user()->interests ?? [],
            fn($i) => !in_array($i, $categoriasPredefinidas)
        );

        return view('habitos.create', compact('sugerencia', 'interesesPersonalizados'));
    }

    /**
     * Valida y guarda un nuevo hábito.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $datos = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'category'         => ['nullable', 'string', 'max:100'],
            'type'             => ['required', 'in:hacer,dejar'],
            'target_per_week'  => ['required_if:type,hacer', 'nullable', 'integer', 'min:1', 'max:7'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:480'],
        ], [
            'title.required'              => 'El título es obligatorio.',
            'type.required'               => 'Debes indicar el tipo de hábito.',
            'type.in'                     => 'El tipo debe ser "hacer" o "dejar".',
            'target_per_week.required_if' => 'Indica cuántos días a la semana quieres cumplir este hábito.',
            'target_per_week.min'         => 'El mínimo es 1 día a la semana.',
            'target_per_week.max'         => 'El máximo es 7 días a la semana.',
        ]);

        // comprobamos si ya existe un hábito activo con el mismo título (sin distinción de mayúsculas)
        $existe = $user->habits()
            ->where('active', true)
            ->whereRaw('LOWER(title) = ?', [strtolower($datos['title'])])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['title' => 'Ya tienes un hábito activo con ese nombre.']);
        }

        $suggestedHabitId = $request->input('suggested_habit_id');

        $this->habitService->crear($user, $datos, $suggestedHabitId);

        return redirect()->route('habitos.index')
            ->with('exito', '¡Hábito creado! Empieza a cumplirlo para ganar XP. ⭐');
    }

    /**
     * Formulario para editar un hábito existente.
     */
    public function edit(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        $categoriasPredefinidas = ['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad', 'aprendizaje', 'creatividad', 'descanso', 'social', 'finanzas', 'hogar', 'naturaleza'];

        $interesesPersonalizados = array_filter(
            auth()->user()->interests ?? [],
            fn($i) => !in_array($i, $categoriasPredefinidas)
        );

        return view('habitos.edit', compact('habito', 'interesesPersonalizados'));
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
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:480'],
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
    public function registrar(Habit $habito, Request $request)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        // calculamos la racha ANTES de registrar el fallo
        $rachaAnterior = $habito->type === 'dejar'
            ? $this->habitService->calcularRachaDejar($habito)
            : 0;

        $registrado = $this->habitService->registrarHoy($habito);

        $destino = $request->input('origen') === 'dashboard'
            ? route('dashboard')
            : route('habitos.index');

        if (!$registrado) {
            return redirect($destino)
                ->with('info', 'Ya has registrado este hábito hoy.');
        }

        if ($habito->type === 'dejar') {
            $mensajeFallo = $rachaAnterior > 0
                ? 'Fallo registrado. Llevabas ' . $rachaAnterior . ' ' . ($rachaAnterior === 1 ? 'día' : 'días') . ' sin fallar. ¡Mañana puedes volver a empezar! 💪'
                : 'Fallo registrado. ¡Mañana es una nueva oportunidad! 💪';

            return redirect($destino)->with('info', $mensajeFallo);
        }

        $subioNivel      = $this->habitService->otorgarXp(auth()->user(), $habito);
        $insigniasNuevas = $this->badgeService->comprobarInsignias(auth()->user());

        $mensaje = $subioNivel
            ? '¡LEVEL UP! Has subido de nivel. 🎉 +' . HabitService::XP_HABITO . ' XP'
            : '¡Hábito registrado! +' . HabitService::XP_HABITO . ' XP ⭐';

        $redirect = redirect($destino)->with('exito', $mensaje);

        if ($insigniasNuevas->isNotEmpty()) {
            $redirect = $redirect->with('insignia_desbloqueada', [
                'nombre' => $insigniasNuevas->first()->name,
                'icono'  => $insigniasNuevas->first()->icon,
            ]);
        }

        return $redirect;
    }

    /**
     * Reactiva un hábito archivado.
     */
    public function recuperar(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        // comprobamos si ya tiene un hábito activo con el mismo nombre
        $existe = auth()->user()->habits()
            ->where('active', true)
            ->whereRaw('LOWER(title) = ?', [strtolower($habito->title)])
            ->exists();

        if ($existe) {
            return redirect()->route('habitos.index')
                ->with('info', 'Ya tienes un hábito activo con ese nombre.');
        }

        $this->habitService->recuperar($habito);

        return redirect()->route('habitos.index')
            ->with('exito', 'Hábito recuperado. ¡A por ello! 💪');
    }

    public function destroyPermanente(Habit $habito)
    {
        abort_if($habito->user_id !== auth()->id(), 403);

        $this->habitService->eliminar($habito);

        return redirect()->route('habitos.index')
            ->with('exito', 'Hábito eliminado definitivamente.');
    }
}
