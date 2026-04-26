@extends('layouts.main-layout')

@section('titulo', 'Editar Misión')

@section('contenido')

<div class="pagina-titulo">
    <h2>✏️ Editar Misión</h2>
    <a href="{{ route('tareas.index') }}" class="btn-primario btn-secundario">← Volver</a>
</div>

<div class="bloque bloque-formulario">
    <form action="{{ route('tareas.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- título --}}
        <div class="campo-grupo">
            <label for="title" class="form-label-levlup">Título de la misión</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-input-levlup {{ $errors->has('title') ? 'input-error' : '' }}"
                value="{{ old('title', $task->title) }}"
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
                placeholder="Añade más detalles si lo necesitas...">{{ old('description', $task->description) }}</textarea>
            @error('description')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- fecha y hora --}}
        <div class="campo-grupo">
            <label class="form-label-levlup">¿Cuándo es la misión?</label>

            {{-- selector rápido --}}
            <div class="fecha-opciones">
                <button type="button" class="btn-fecha-rapida {{ $task->scheduled_at->isToday() ? 'activo' : '' }}" data-opcion="hoy">
                    ⚡ Hoy
                </button>
                <button type="button" class="btn-fecha-rapida {{ !$task->scheduled_at->isToday() ? 'activo' : '' }}" data-opcion="programar">
                    🗓 Programar
                </button>
            </div>

            {{-- campo fecha --}}
            <div id="campo-fecha" class="mt-2" style="{{ !$task->scheduled_at->isToday() ? 'display: block;' : 'display: none;' }}">
                <label for="fecha" class="form-label-levlup">Fecha</label>
                <input
                    type="date"
                    id="fecha"
                    class="form-input-levlup {{ $errors->has('scheduled_at') ? 'input-error' : '' }}"
                    min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('fecha', $task->scheduled_at->format('Y-m-d')) }}"
                    data-hoy="{{ now()->format('Y-m-d') }}">
            </div>

            {{-- campo hora --}}
            <div id="campo-hora" class="mt-2" style="display: block;">
                <label for="hora" class="form-label-levlup">Hora</label>
                <div class="hora-selector">
                    <span class="hora-icono">🕐</span>
                    <input
                        type="time"
                        id="hora"
                        class="form-input-levlup"
                        value="{{ old('hora', $task->scheduled_at->format('H:i')) }}">
                </div>
            </div>

            {{-- campo oculto --}}
            <input
                type="hidden"
                name="scheduled_at"
                id="scheduled_at"
                value="{{ $task->scheduled_at->format('Y-m-d H:i:s') }}">

            @error('scheduled_at')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" id="btn-submit" class="btn-primario">💾 Guardar cambios</button>

    </form>
</div>

@endsection