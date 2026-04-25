<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro (paso 1).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Crea el usuario con los datos básicos y redirige al paso 2.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar_seed' => ['required', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'avatar_seed' => $request->avatar_seed,
            'interests'   => [],
            'points'      => 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // redirigimos al paso 2 para que el usuario configure sus intereses
        return redirect()->route('registro.intereses');
    }

    /**
     * Muestra el formulario de intereses (paso 2).
     */
    public function intereses(): View
    {
        return view('auth.intereses', [
            'accion' => route('registro.intereses.store'),
            'redireccion' => route('dashboard'),
        ]);
    }

    /**
     * Guarda los intereses del usuario recién registrado.
     */
    public function storeIntereses(Request $request): RedirectResponse
    {
        $request->validate([
            'interests'   => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
        ]);

        auth()->user()->update([
            'interests' => $request->input('interests', []),
        ]);

        return redirect()->route('dashboard')
            ->with('exito', '¡Bienvenido a LevlUp! Ya tienes todo listo. 🎮');
    }
}