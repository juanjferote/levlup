<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use App\Services\BadgeService;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $perfilService,
        private BadgeService   $badgeService,
    ) {}

    /* Muestra la página de perfil del usuario autenticado. */
    public function index()
    {
        return view('perfil.index', [
            'usuario' => auth()->user(),
        ]);
    }

    /* Un solo método maneja tanto datos básicos como intereses porque comparten vista.
     * La ruta activa determina qué bloque de validación y actualización se ejecuta. */
    public function update(Request $request)
    {
        $usuario = auth()->user();

        if ($request->routeIs('perfil.intereses')) {
            $request->validate([
                'interests'   => ['nullable', 'array'],
                'interests.*' => ['string', 'max:50'],
            ]);

            $this->perfilService->actualizarIntereses($usuario, $request->all());

            // los intereses personalizados desbloquean insignias específicas
            $insigniasNuevas = $this->badgeService->comprobarInsignias($usuario);

            $mensaje = 'Intereses actualizados correctamente.';

            $redirect = redirect()->route('perfil.index')->with('exito', $mensaje);

            if ($insigniasNuevas->isNotEmpty()) {
                $redirect = $redirect->with('insignia_desbloqueada', [
                    'nombre' => $insigniasNuevas->first()->name,
                    'icono'  => $insigniasNuevas->first()->icon,
                ]);
            }

            return $redirect;
        }

        $request->validateWithBag('datosBasicos', [
            'name'        => ['required', 'string', 'max:12'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'avatar_seed' => ['required', 'string', 'in:warrior,mage,archer,ninja,knight,rogue,healer,bard,ranger,paladin,druid,monk'],
        ]);

        $this->perfilService->actualizarDatosBasicos($usuario, $request->all());

        return back()->with('exito', 'Perfil actualizado correctamente.');
    }
    /* Valida la contraseña actual antes de permitir el cambio, usando el bag 'password'
     * para separar los errores de los del formulario de datos básicos. */
    public function updatePassword(Request $request)
    {
        $usuario = auth()->user();

        $request->validateWithBag('password', [
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $this->perfilService->actualizarPassword($usuario, $request->only('password'));

        return back()->with('exito', 'Contraseña actualizada correctamente.');
    }
}
