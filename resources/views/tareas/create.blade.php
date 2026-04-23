@extends('layouts.main-layout')

@section('titulo', 'Nueva Misión')

@section('contenido')

<div class="pagina-titulo">
    <h2>➕ Nueva Misión</h2>
    <a href="{{ route('tareas.index') }}" class="btn-primario btn-secundario">← Volver</a>
</div>

<div class="bloque bloque-formulario">
    <form action="{{ route('tareas.store') }}" method="POST">
        @csrf

        {{-- título --}}
        <div class="campo-grupo">
            <label for="title" class="form-label-levlup">Título de la misión</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-input-levlup {{ $errors->has('title') ? 'input-error' : '' }}"
                value="{{ old('title') }}"
                placeholder="Ej: Ir al médico"
                autofocus>
            @error('title')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- descripción --}}
        <div class="campo-grupo">
            <label for="description" class="form-label-levlup">Descripción <span class="texto-suave">(opcional)</span></label>
            <textarea
                id="description"
                name="description"
                class="form-input-levlup {{ $errors->has('description') ? 'input-error' : '' }}"
                rows="3"
                placeholder="Añade más detalles si lo necesitas...">{{ old('description') }}</textarea>
            @error('description')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- fecha y hora --}}
        <div class="campo-grupo">
            <label class="form-label-levlup">¿Cuándo es la misión?</label>

            {{-- selector rápido --}}
            <div class="fecha-opciones">
                <button type="button" class="btn-fecha-rapida" data-opcion="hoy">
                    ⚡ Hoy
                </button>
                <button type="button" class="btn-fecha-rapida" data-opcion="programar">
                    🗓 Programar
                </button>
            </div>

            {{-- campo fecha (solo visible al pulsar "Programar") --}}
            <div id="campo-fecha" class="mt-2" style="display: none;">
                <label for="fecha" class="form-label-levlup">Fecha</label>
                <input
                    type="date"
                    id="fecha"
                    class="form-input-levlup {{ $errors->has('scheduled_at') ? 'input-error' : '' }}"
                    min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('fecha') }}"
                    data-hoy="{{ now()->format('Y-m-d') }}">
            </div>

            {{-- campo hora (visible al pulsar cualquier botón) --}}
            <div id="campo-hora" class="mt-2" style="display: none;">
                <label for="hora" class="form-label-levlup">Hora</label>
                <div class="hora-selector">
                    <input
                        type="time"
                        id="hora"
                        class="form-input-levlup"
                        value="{{ old('hora') }}">
                </div>
            </div>

            {{-- campo oculto que se envía al servidor --}}
            <input type="hidden" name="scheduled_at" id="scheduled_at">

            @error('scheduled_at')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" id="btn-submit" class="btn-primario" disabled>⭐ Crear misión</button>

    </form>
</div>

@endsection
@push('scripts')
<script src="{{ asset('js/tareas.js') }}"></script>
@endpush