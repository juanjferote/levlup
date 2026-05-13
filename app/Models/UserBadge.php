<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    // no necesitamos created_at ni updated_at, solo unlocked_at
    public $timestamps = false;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'badge_id',
        'unlocked_at',
    ];

    // conversión automática de tipos
    protected function casts(): array
    {
        return [
            'unlocked_at' => 'datetime', // se castea a Carbon para poder ordenar y formatear la fecha de desbloqueo
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}