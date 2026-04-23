<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestedHabit extends Model
{
    use HasFactory;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty_level',
        'suggested_target_per_week',
        'suggested_duration_minutes',
    ];

    // scope para filtrar hábitos sugeridos según categoría e intervalo de dificultad
    // se usa para mostrar al usuario sugerencias acordes a su nivel e intereses
    public function scopeForUser($query, array $categories, int $minLevel, int $maxLevel)
    {
        return $query->whereIn('category', $categories)
            ->whereBetween('difficulty_level', [$minLevel, $maxLevel]);
    }
}