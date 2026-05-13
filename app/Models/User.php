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

    // nivel máximo que puede alcanzar un usuario
    const NIVEL_MAXIMO = 20;

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

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /* Único punto de entrada para modificar puntos: centralizar aquí garantiza que nivel
     * y puntos nunca se desincronizan. Devuelve true si el usuario ha subido de nivel. */
    public function addPoints(int $amount): bool
    {
        $this->increment('points', $amount);
        $newLevel = $this->calculateLevel();

        if ($newLevel > $this->level) {
            $this->update(['level' => $newLevel]);
            return true; // el controlador usa este true para mostrar el aviso de "level up"
        }

        return false;
    }

    /* Calcula el nivel según los puntos acumulados. El tramo para subir al nivel N+1 cuesta 100*N puntos,
     * por lo que los tramos crecen: nivel 2 = 100pts, nivel 3 = 300pts totales, nivel 4 = 600pts, etc. */
    public function calculateLevel(): int
    {
        $level       = 1;
        $limit       = 100; // puntos que cuesta el TRAMO actual (nivel 1→2)
        $accumulated = 0;   // puntos acumulados hasta el inicio del tramo actual

        while ($this->points >= $accumulated + $limit && $level < self::NIVEL_MAXIMO) {
            $accumulated += $limit;    // sumamos el tramo que acabamos de superar
            $level++;                  // subimos de nivel
            $limit = 100 * $level;     // el siguiente tramo cuesta más
        }

        return $level;
    }

    /* Devuelve los puntos que le faltan al usuario para subir de nivel.
     * Se usa para calcular el porcentaje de la barra de progreso en la vista. */
    public function pointsToNextLevel(): int
    {
        if ($this->level >= self::NIVEL_MAXIMO) {
            return 0;
        }

        $accumulatedForCurrentLevel = 0;
        for ($i = 1; $i < $this->level; $i++) {
            $accumulatedForCurrentLevel += 100 * $i;
        }

        $neededForNextLevel = $accumulatedForCurrentLevel + (100 * $this->level);
        return $neededForNextLevel - $this->points;
    }

    public function avatarUrl(): string
    {
        return 'https://api.dicebear.com/9.x/pixel-art/svg?seed=' . urlencode($this->avatar_seed) . '&size=80';
    }
}