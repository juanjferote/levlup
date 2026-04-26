<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskService
{
    // XP fijo que se otorga al completar una tarea
    const XP_TAREA = 10;

    /**
     * Devuelve las tareas del usuario agrupadas por fecha,
     * listas para pasar a la vista.
     */
    public function tareasAgrupadas(User $user): array
    {
        $tareas = $user->tasks()->orderBy('scheduled_at')->get();

        return [
            'tareasHoy' => $tareas->filter(fn($t) => $t->scheduled_at->isToday() && !$t->completed),
            'tareasFuturas'  => $tareas->filter(fn($t) => $t->scheduled_at->isFuture() && !$t->scheduled_at->isToday()),
            'tareasVencidas' => $tareas->filter(fn($t) => $t->scheduled_at->isPast() && !$t->scheduled_at->isToday() && !$t->completed),
        ];
    }

    /**
     * Crea una nueva tarea asociada al usuario.
     */
    public function crear(User $user, array $datos): Task
    {
        $datos['user_id'] = $user->id;
        return Task::create($datos);
    }

    /**
     * Actualiza los datos de una tarea existente.
     */
    public function actualizar(Task $task, array $datos): void
    {
        $task->update($datos);
    }

    /**
     * Marca una tarea como completada y otorga XP al usuario.
     * Devuelve true si el usuario ha subido de nivel.
     */
    public function completar(Task $task, User $user): bool
    {
        $task->update(['completed' => true]);

        return $user->addPoints(self::XP_TAREA);
    }

    /**
     * Elimina una tarea de la base de datos.
     */
    public function eliminar(Task $task): void
    {
        $task->delete();
    }
}
