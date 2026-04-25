<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    // actualiza nombre, email y avatar del usuario
    public function actualizarDatosBasicos(User $user, array $datos): void
    {
        $user->update([
            'name'        => $datos['name'],
            'email'       => $datos['email'],
            'avatar_seed' => $datos['avatar_seed'],
        ]);
    }

    // actualiza los intereses del usuario
    public function actualizarIntereses(User $user, array $datos): void
    {
        $user->update([
            'interests' => $datos['interests'] ?? [],
        ]);
    }

    // actualiza la contraseña del usuario
    public function actualizarPassword(User $user, array $datos): void
    {
        $user->update([
            'password' => $datos['password'],
        ]);
    }
}
