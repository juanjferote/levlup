<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Services\BadgeService;

class TaskController extends Controller
{
    public function __construct(
        private TaskService  $taskService,
        private BadgeService $badgeService,
    ) {}

    /**
     * Lista de tareas agrupadas por fecha.
     */
    public function index()
    {
        $grupos = $this->taskService->tareasAgrupadas(auth()->user());

        return view('tareas.index', $grupos);
    }

    /**
     * Formulario para crear una nueva tarea.
     */
    public function create()
    {
        return view('tareas.create');
    }

    /**
     * Valida y guarda una nueva tarea.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ], [
            'title.required'              => 'El título es obligatorio.',
            'title.max'                   => 'El título no puede superar los 255 caracteres.',
            'scheduled_at.required'       => 'Debes indicar la fecha y la hora de la misión.',
            'scheduled_at.date'           => 'La fecha no tiene un formato válido.',
            'scheduled_at.after' => 'No puedes crear tareas en el pasado.',
        ]);

        $this->taskService->crear(auth()->user(), $datos);

        // comprobamos insignias al crear la primera tarea
        $this->badgeService->comprobarInsignias(auth()->user());

        return redirect()->route('tareas.index')
            ->with('exito', '¡Misión creada! Complétala para ganar XP. ⭐');
    }

    /**
     * Formulario para editar una tarea existente.
     */
    public function edit(Task $tarea)
    {
        abort_if($tarea->user_id != auth()->id(), 403);
        return view('tareas.edit', ['task' => $tarea]);
    }

    /**
     * Valida y actualiza una tarea existente.
     */
    public function update(Request $request, Task $tarea)
    {
        abort_if($tarea->user_id != auth()->id(), 403);

        $datos = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['required', 'date'],
        ], [
            'title.required'        => 'El título es obligatorio.',
            'title.max'             => 'El título no puede superar los 255 caracteres.',
            'scheduled_at.required' => 'Debes indicar la fecha y la hora de la misión.',
            'scheduled_at.date'     => 'La fecha no tiene un formato válido.',
        ]);

        $this->taskService->actualizar($tarea, $datos);

        return redirect()->route('tareas.index')
            ->with('exito', 'Misión actualizada correctamente. ✏️');
    }

    /**
     * Marca una tarea como completada y otorga XP.
     */
    public function completar(Task $tarea)
    {
        abort_if($tarea->user_id != auth()->id(), 403);

        if ($tarea->completed) {
            return redirect()->route('tareas.index')
                ->with('info', 'Esta misión ya estaba completada.');
        }

        $subioNivel = $this->taskService->completar($tarea, auth()->user());

        // la tarea es futura y no se puede completar
        if (!$tarea->fresh()->completed) {
            return redirect()->route('tareas.index')
                ->with('info', 'No puedes completar una misión que aún no ha llegado.');
        }

        $insigniasNuevas = $this->badgeService->comprobarInsignias(auth()->user());

        $mensaje = $subioNivel
            ? '¡LEVEL UP! Has subido de nivel. 🎉 +' . TaskService::XP_TAREA . ' XP'
            : '¡Misión completada! +' . TaskService::XP_TAREA . ' XP ⭐';

        $redirect = redirect()->route('tareas.index')->with('exito', $mensaje);

        if ($insigniasNuevas->isNotEmpty()) {
            $redirect = $redirect->with('insignia_desbloqueada', [
                'nombre' => $insigniasNuevas->first()->name,
                'icono'  => $insigniasNuevas->first()->icon,
            ]);
        }

        return $redirect;
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Task $tarea)
    {
        abort_if($tarea->user_id != auth()->id(), 403);

        $this->taskService->eliminar($tarea);

        return redirect()->route('tareas.index')
            ->with('exito', 'Misión eliminada.');
    }
}
