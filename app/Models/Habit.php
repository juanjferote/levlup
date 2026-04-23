<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HabitLog;

class Habit extends Model
{
    use HasFactory;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_per_week',
        'custom_days',
        'active',
        'suggested_by_system',
        'difficulty_level',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'custom_days'         => 'array',
            'active'              => 'boolean',
            'suggested_by_system' => 'boolean',
        ];
    }

    // un hábito pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // un hábito tiene muchos registros de cumplimiento
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}