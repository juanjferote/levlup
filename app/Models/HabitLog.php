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
            'logged_date' => 'date', // se castea a Carbon para poder usar diffInDays() y comparaciones de fecha
        ];
    }

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}