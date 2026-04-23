<?php use Illuminate\Support\Facades\Route; ?>

<x-guest-layout>

    <div class="bloque">

        <div class="bloque-titulo">Iniciar sesión</div>

        @if (session('status'))
        <p class="form-status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
        <p class="form-error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label-levlup">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="form-input-levlup">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label-levlup">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="form-input-levlup">
            </div>

            <div class="mb-3">
                <label class="form-check-recordar">
                    <input type="checkbox" name="remember">
                    Recuérdame
                </label>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="form-link">
                    ¿Olvidaste tu contraseña?
                </a>
                @endif

                <button type="submit" class="btn-primario">
                    Entrar
                </button>
            </div>

            <div class="mt-3 text-center">
                <span class="form-link">¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="form-link-destacado">Regístrate</a>
                </span>
            </div>

        </form>
    </div>

</x-guest-layout>