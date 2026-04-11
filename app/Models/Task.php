<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'scheduled_at',
        'completed',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime', // Laravel convierte el campo a objeto Carbon automáticamente
            'completed'    => 'boolean',
        ];
    }

    // una tarea pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}