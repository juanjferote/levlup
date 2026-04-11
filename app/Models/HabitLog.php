<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    use HasFactory;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'habit_id',
        'logged_date',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'logged_date' => 'date', // Laravel convierte el campo a objeto Carbon automáticamente
        ];
    }

    // un registro pertenece a un hábito
    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}