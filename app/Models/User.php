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
    ];

    // campos ocultos en serialización (ej: cuando devolvemos JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'interests' => 'array', // Laravel convierte el JSON a array de PHP automáticamente
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
}