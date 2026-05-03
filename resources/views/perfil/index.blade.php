@extends('layouts.main-layout')

@section('titulo', 'Mi perfil')

@section('contenido')

<div class="pagina-titulo">
    <h2>Mi perfil</h2>
</div>

<div class="perfil-contenido">

    {{-- Fila superior: datos básicos + contraseña --}}
    <div class="perfil-fila">

        {{-- Datos básicos --}}
        <div class="bloque perfil-seccion">
            <div class="bloque-titulo">// Datos básicos</div>

            @if ($errors->datosBasicos->any())
            <p class="form-error">{{ $errors->datosBasicos->first() }}</p>
            @endif

            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PATCH')

                <div class="campo-grupo">
                    <label for="name" class="form-label-levlup">Nombre</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $usuario->name) }}"
                        required autocomplete="name"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="email" class="form-label-levlup">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $usuario->email) }}"
                        required autocomplete="email"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label class="form-label-levlup">Avatar</label>
                    <div class="avatar-selector-grid">
                        @foreach (['warrior', 'mage', 'archer', 'ninja', 'knight', 'rogue', 'healer', 'bard', 'ranger', 'paladin', 'druid', 'monk'] as $semilla)
                        <label class="avatar-opcion">
                            <input type="radio" name="avatar_seed" value="{{ $semilla }}"
                                {{ old('avatar_seed', $usuario->avatar_seed) === $semilla ? 'checked' : '' }}>
                            <img src="https://api.dicebear.com/9.x/pixel-art/svg?seed={{ $semilla }}&size=64"
                                alt="{{ $semilla }}" width="54" height="54">
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

        {{-- Contraseña --}}
        <div class="bloque perfil-seccion">
            <div class="bloque-titulo">// Contraseña</div>

            @if ($errors->password->any())
            <p class="form-error">{{ $errors->password->first() }}</p>
            @endif

            <form method="POST" action="{{ route('perfil.password') }}">
                @csrf
                @method('PATCH')

                <div class="campo-grupo">
                    <label for="current_password" class="form-label-levlup">Contraseña actual</label>
                    <input type="password" id="current_password" name="current_password"
                        required autocomplete="current-password"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="password" class="form-label-levlup">Nueva contraseña</label>
                    <input type="password" id="password" name="password"
                        required autocomplete="new-password"
                        class="form-input-levlup">
                </div>

                <div class="campo-grupo">
                    <label for="password_confirmation" class="form-label-levlup">Confirmar nueva contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        required autocomplete="new-password"
                        class="form-input-levlup">
                </div>

                <button type="submit" class="btn-primario">Cambiar contraseña</button>
            </form>
        </div>

    </div>

    {{-- Fila inferior: intereses --}}
    <div class="bloque perfil-intereses-card">
        <div class="perfil-intereses-info">
            <div class="bloque-titulo">// Tus intereses</div>
            <p class="perfil-intereses-desc">Estas son las áreas en las que estás trabajando. ¿Quieres añadir o modificar alguna?</p>
            <div class="perfil-intereses-tags">
                @forelse($usuario->interests ?? [] as $interes)
                <span class="perfil-interes-tag">{{ ucfirst($interes) }}</span>
                @empty
                <span class="texto-suave">No has seleccionado intereses aún.</span>
                @endforelse
            </div>
        </div>
        <button type="button" class="btn-secundario" data-bs-toggle="modal" data-bs-target="#modal-intereses">
            ✎ Editar intereses
        </button>
    </div>

</div>

{{-- Modal de intereses --}}
<div class="modal fade" id="modal-intereses" tabindex="-1" aria-labelledby="modal-intereses-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content perfil-modal">
            <div class="modal-header perfil-modal__header">
                <h5 class="bloque-titulo" id="modal-intereses-label">// Editar intereses</h5>
                <button type="button" class="btn-icono" data-bs-dismiss="modal" aria-label="Cerrar">✕</button>
            </div>
            <div class="modal-body perfil-modal__body">
                @include('partials._intereses', [
                'accion' => route('perfil.intereses'),
                'interesesActivos' => $usuario->interests ?? [],
                'textBoton' => 'Guardar intereses',
                ])
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/intereses.js') }}"></script>
<script src="{{ asset('js/perfil.js') }}"></script>
@endpush