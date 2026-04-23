<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Task;
use App\Models\Habit;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'interests',
        'points',
        'level',
        'avatar_seed',
    ];

    // campos ocultos en serialización
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'interests' => 'array',
        ];
    }

    // un usuario tiene muchas tareas
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // un usuario tiene muchos hábitos
    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    // un usuario tiene muchas insignias desbloqueadas
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    // suma puntos al usuario y actualiza su nivel si procede
    // este es el ÚNICO método que debe usarse para modificar puntos,
    // así garantizamos que nivel y puntos nunca se desincronizan
    public function addPoints(int $amount): bool
    {
        $this->increment('points', $amount);
        $newLevel = $this->calculateLevel();

        // si el nivel calculado es mayor que el actual, subimos de nivel
        if ($newLevel > $this->level) {
            $this->update(['level' => $newLevel]);
            return true; // devolvemos true para que el controlador pueda mostrar el "level up"
        }

        return false;
    }

    // calcula el nivel del usuario en función de sus puntos acumulados
    // fórmula: para pasar al nivel N+1 hacen falta 100*N puntos acumulados
    // nivel 2 = 100 puntos, nivel 3 = 300, nivel 4 = 600, nivel 5 = 1000, etc.
    public function calculateLevel(): int
    {
        $level = 1;
        $limit = 100; // puntos que cuesta el TRAMO actual (nivel 1→2)
        $accumulated = 0; // puntos acumulados hasta el inicio del tramo actual

        while ($this->points >= $accumulated + $limit && $level < 10) {
            $accumulated += $limit; // sumamos el tramo que acabamos de superar 
            $level++;       // subimos de nivel
            $limit = 100 * $level; // el siguiente tramo cuesta más
        }

        return $level;
    }

    // devuelve los puntos que faltan para alcanzar el siguiente nivel
    // útil para la barra de progreso visual
    public function pointsToNextLevel(): int
    {
        if ($this->level >= 10) {
            return 0; // ya está en el nivel máximo
        }

        $accumulatedForCurrentLevel = 0;
        for ($i = 1; $i < $this->level; $i++) {
            $accumulatedForCurrentLevel += 100 * $i;
        }

        $neededForNextLevel = $accumulatedForCurrentLevel + (100 * $this->level);
        return $neededForNextLevel - $this->points;
    }
    // devuelve la URL del avatar generado por DiceBear según la semilla del usuario
    public function avatarUrl(): string
    {
        return 'https://api.dicebear.com/9.x/pixel-art/svg?seed=' . urlencode($this->avatar_seed) . '&size=80';
    }
}
