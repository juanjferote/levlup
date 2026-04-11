<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserBadge;

class Badge extends Model
{
    use HasFactory;

    // las insignias son un catálogo estático, no tienen timestamps
    public $timestamps = false;

    // campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'description',
        'icon',
        'condition_type',
        'condition_value',
    ];

    // una insignia puede haber sido desbloqueada por muchos usuarios
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
}