<?php

namespace App\Services;

use App\Models\User;

class NivelService
{
    /**
     * Devuelve los datos de progreso de nivel del usuario,
     * listos para pasar directamente a la vista.
     */
    public function datosProgreso(User $user): array
    {
        $tramoCosto      = 100 * $user->level;
        $faltan          = $user->pointsToNextLevel();
        $yaHechos        = $tramoCosto - $faltan;
        $porcentajeNivel = $user->level >= 10
            ? 100
            : round(($yaHechos / $tramoCosto) * 100);

        return [
            'nivel'           => $user->level,
            'puntos'          => $user->points,
            'porcentajeNivel' => $porcentajeNivel,
            'faltan'          => $faltan,
        ];
    }
}