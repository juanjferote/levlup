<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $perfilService) {}

    // muestra la página de perfil del usuario autenticado
    public function index()
    {
        return view('perfil.index', [
            'usuario' => auth()->user(),
        ]);
    }

    // actualiza datos básicos o intereses según la ruta de origen
    public function update(Request $request)
    {
        $usuario = auth()->user();

        if ($request->routeIs('perfil.intereses')) {
            $request->validate([
                'interests'   => ['nullable', 'array'],
                'interests.*' => ['string', 'max:50'],
            ]);

            $this->perfilService->actualizarIntereses($usuario, $request->all());

            return back()->with('exito', 'Intereses actualizados correctamente.');
        }

        $request->validateWithBag('datosBasicos', [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'avatar_seed' => ['required', 'string', 'in:warrior,mage,archer,ninja,knight,rogue,healer,bard,ranger,paladin,druid,monk'],
        ]);

        $this->perfilService->actualizarDatosBasicos($usuario, $request->all());

        return back()->with('exito', 'Perfil actualizado correctamente.');
    }

    // actualiza la contraseña del usuario autenticado
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
