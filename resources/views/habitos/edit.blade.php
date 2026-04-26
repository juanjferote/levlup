@extends('layouts.main-layout')

@section('titulo', 'Editar Hábito')

@section('contenido')

<div class="pagina-titulo">
    <h2>✏️ Editar Hábito</h2>
    <a href="{{ route('habitos.index') }}" class="btn-secundario">← Volver</a>
</div>

<div class="bloque bloque-formulario">
    <form action="{{ route('habitos.update', $habito) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- título --}}
        <div class="campo-grupo">
            <label for="title" class="form-label-levlup">Nombre del hábito</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-input-levlup {{ $errors->has('title') ? 'input-error' : '' }}"
                value="{{ old('title', $habito->title) }}"
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
                rows="3">{{ old('description', $habito->description) }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- frecuencia, duración y categoría (solo si es tipo hacer) --}}
        @if($habito->type === 'hacer')

            <div class="campo-grupo">
                <label for="target_per_week" class="form-label-levlup">¿Cuántos días a la semana?</label>
                <div class="frecuencia-selector">
                    @for($i = 1; $i <= 7; $i++)
                        <label class="frecuencia-opcion {{ old('target_per_week', $habito->target_per_week) == $i ? 'activo' : '' }}">
                            <input type="radio" name="target_per_week" value="{{ $i }}"
                                {{ old('target_per_week', $habito->target_per_week) == $i ? 'checked' : '' }}>
                            {{ $i }}
                        </label>
                    @endfor
                </div>
                @error('target_per_week')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="campo-grupo">
                <label for="duration_minutes" class="form-label-levlup">Duración <span class="texto-suave">(minutos, opcional)</span></label>
                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    class="form-input-levlup {{ $errors->has('duration_minutes') ? 'input-error' : '' }}"
                    value="{{ old('duration_minutes', $habito->duration_minutes) }}"
                    min="1"
                    max="480"
                    placeholder="Ej: 30">
                @error('duration_minutes')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="campo-grupo">
                <label for="category" class="form-label-levlup">Categoría <span class="texto-suave">(opcional)</span></label>
                <select id="category" name="category" class="form-input-levlup">
                    <option value="">Sin categoría</option>
                    @foreach(['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad', 'aprendizaje', 'creatividad', 'sueno', 'social', 'finanzas', 'hogar', 'naturaleza'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $habito->category) === $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

        @endif

        <button type="submit" class="btn-primario">💾 Guardar cambios</button>

    </form>
</div>

@endsection