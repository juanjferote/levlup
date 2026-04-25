@extends('layouts.main-layout')

@section('titulo', 'Mi perfil')

@section('contenido')

    <div class="pagina-titulo">
        <h2>⚙️ Mi perfil</h2>
    </div>

    @if (session('exito'))
        <div class="alerta alerta-exito">{{ session('exito') }}</div>
    @endif

    <div class="perfil-contenido">

        {{-- Sección 1: Datos básicos --}}
        <div class="bloque perfil-seccion mb-4">

            <div class="bloque-titulo">Datos básicos</div>

            @if ($errors->datosBasicos->any())
                <p class="form-error">{{ $errors->datosBasicos->first() }}</p>
            @endif

            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PATCH')

                <div class="campo-grupo">
                    <label for="name" class="form-label-levlup">Nombre</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $usuario->name) }}"
                        required
                        autocomplete="name"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="email" class="form-label-levlup">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $usuario->email) }}"
                        required
                        autocomplete="email"
                        class="form-input-levlup">
                </div>

                {{-- selector de avatar --}}
                <div class="campo-grupo">
                    <label class="form-label-levlup">Avatar</label>
                    <div class="avatar-selector-grid">
                        @foreach (['warrior', 'mage', 'archer', 'ninja', 'knight', 'rogue', 'healer', 'bard', 'ranger', 'paladin', 'druid', 'monk'] as $semilla)
                            <label class="avatar-opcion">
                                <input
                                    type="radio"
                                    name="avatar_seed"
                                    value="{{ $semilla }}"
                                    {{ old('avatar_seed', $usuario->avatar_seed) === $semilla ? 'checked' : '' }}>
                                <img
                                    src="https://api.dicebear.com/9.x/pixel-art/svg?seed={{ $semilla }}&size=64"
                                    alt="{{ $semilla }}"
                                    width="54"
                                    height="54">
                            </label>
                        @endforeach
                    </div>
                    @if ($errors->datosBasicos->has('avatar_seed'))
                        <span class="form-error">{{ $errors->datosBasicos->first('avatar_seed') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn-primario">Guardar perfil</button>

            </form>
        </div>

        {{-- Sección 2: Intereses --}}
        <div class="bloque perfil-seccion mb-4">

            <div class="bloque-titulo">Intereses</div>

            @include('partials._intereses', [
                'accion'           => route('perfil.intereses'),
                'interesesActivos' => $usuario->interests ?? [],
                'textBoton'        => 'Guardar intereses',
            ])

        </div>

        {{-- Sección 3: Contraseña --}}
        <div class="bloque perfil-seccion">

            <div class="bloque-titulo">Contraseña</div>

            @if ($errors->password->any())
                <p class="form-error">{{ $errors->password->first() }}</p>
            @endif

            <form method="POST" action="{{ route('perfil.password') }}">
                @csrf
                @method('PATCH')

                <div class="campo-grupo">
                    <label for="current_password" class="form-label-levlup">Contraseña actual</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="password" class="form-label-levlup">Nueva contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="password_confirmation" class="form-label-levlup">Confirmar nueva contraseña</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="form-input-levlup">
                </div>

                <button type="submit" class="btn-primario">Cambiar contraseña</button>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/intereses.js') }}"></script>
    <script src="{{ asset('js/perfil.js') }}"></script>
@endpush
