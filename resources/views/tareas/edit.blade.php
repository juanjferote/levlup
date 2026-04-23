@extends('layouts.main-layout')

@section('titulo', 'Editar Misión')

@section('contenido')

    <div class="pagina-titulo">
        <h2>✏️ Editar Misión</h2>
        <a href="{{ route('tareas.index') }}" class="btn-primario btn-secundario">← Volver</a>
    </div>

    <div class="bloque">
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
                    autofocus
                >
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
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- fecha --}}
            <div class="campo-grupo">
                <label for="scheduled_at" class="form-label-levlup">Fecha</label>
                <input
                    type="date"
                    id="scheduled_at"
                    name="scheduled_at"
                    class="form-input-levlup {{ $errors->has('scheduled_at') ? 'input-error' : '' }}"
                    value="{{ old('scheduled_at', $task->scheduled_at->format('Y-m-d')) }}"
                >
                @error('scheduled_at')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primario">💾 Guardar cambios</button>

        </form>
    </div>

@endsection