<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    /* Actualiza nombre, email y semilla del avatar del usuario. */
    public function actualizarDatosBasicos(User $user, array $datos): void
    {
        $user->update([
            'name'        => $datos['name'],
            'email'       => $datos['email'],
            'avatar_seed' => $datos['avatar_seed'],
        ]);
    }

    /* Guarda los intereses del usuario; si no se envían, se vacía el array. */
    public function actualizarIntereses(User $user, array $datos): void
    {
        $user->update([
            'interests' => $datos['interests'] ?? [],
        ]);
    }

    /* Actualiza la contraseña del usuario (el hash lo aplica el cast del modelo). */
    public function actualizarPassword(User $user, array $datos): void
    {
        $user->update([
            'password' => $datos['password'],
        ]);
    }
}
