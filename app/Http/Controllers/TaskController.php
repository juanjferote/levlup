<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

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
            'scheduled_at' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'title.required'              => 'El título es obligatorio.',
            'title.max'                   => 'El título no puede superar los 255 caracteres.',
            'scheduled_at.required'       => 'La fecha es obligatoria.',
            'scheduled_at.date'           => 'La fecha no tiene un formato válido.',
            'scheduled_at.after_or_equal' => 'No puedes crear tareas en el pasado.',
        ]);

        $this->taskService->crear(auth()->user(), $datos);

        return redirect()->route('tareas.index')
            ->with('exito', '¡Misión creada! Complétala para ganar XP. ⭐');
    }

    /**
     * Formulario para editar una tarea existente.
     */
    public function edit(Task $task)
    {
        dd($task->user_id, auth()->id(), $task->user_id == auth()->id());
        abort_if($task->user_id != auth()->id(), 403);

        return view('tareas.edit', compact('task'));
    }

    /**
     * Valida y actualiza una tarea existente.
     */
    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id != auth()->id(), 403);

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

        $this->taskService->actualizar($task, $datos);

        return redirect()->route('tareas.index')
            ->with('exito', 'Misión actualizada correctamente. ✏️');
    }

    /**
     * Marca una tarea como completada y otorga XP.
     */
    public function completar(Task $task)
    {
        abort_if($task->user_id != auth()->id(), 403);

        if ($task->completed) {
            return redirect()->route('tareas.index')
                ->with('info', 'Esta misión ya estaba completada.');
        }

        $subioNivel = $this->taskService->completar($task, auth()->user());

        $mensaje = $subioNivel
            ? '¡LEVEL UP! Has subido de nivel. 🎉 +' . TaskService::XP_TAREA . ' XP'
            : '¡Misión completada! +' . TaskService::XP_TAREA . ' XP ⭐';

        return redirect()->route('tareas.index')->with('exito', $mensaje);
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Task $task)
    {
        abort_if($task->user_id != auth()->id(), 403);

        $this->taskService->eliminar($task);

        return redirect()->route('tareas.index')
            ->with('exito', 'Misión eliminada.');
    }
}