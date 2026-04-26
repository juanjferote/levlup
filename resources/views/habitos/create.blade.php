@extends('layouts.main-layout')

@section('titulo', 'Nuevo Hábito')

@section('contenido')

<div class="pagina-titulo">
    <h2>➕ Nuevo Hábito</h2>
    <a href="{{ route('habitos.index') }}" class="btn-secundario">← Volver</a>
</div>

<div class="bloque bloque-formulario">
    <form action="{{ route('habitos.store') }}" method="POST">
        @csrf

        {{-- campo oculto si viene de una sugerencia --}}
        @if($sugerencia)
        <input type="hidden" name="suggested_habit_id" value="{{ $sugerencia->id }}">
        @endif

        {{-- título --}}
        <div class="campo-grupo">
            <label for="title" class="form-label-levlup">Nombre del hábito</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-input-levlup {{ $errors->has('title') ? 'input-error' : '' }}"
                value="{{ old('title', $sugerencia?->title) }}"
                placeholder="Ej: Correr 20 minutos"
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
                placeholder="Describe tu hábito...">{{ old('description', $sugerencia?->description) }}</textarea>
            @error('description')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- tipo de hábito --}}
        <div class="campo-grupo">
            <label class="form-label-levlup">Tipo de hábito</label>
            <div class="tipo-opciones">
                <label class="tipo-opcion {{ old('type') === 'hacer' ? 'activo' : '' }}">
                    <input type="radio" name="type" value="hacer" {{ old('type') === 'hacer' ? 'checked' : '' }}>
                    <span class="tipo-icono">✅</span>
                    <span class="tipo-texto">
                        <strong>Quiero hacer</strong>
                        <small>Meditar, correr, leer...</small>
                    </span>
                </label>
                <label class="tipo-opcion {{ old('type') === 'dejar' ? 'activo' : '' }}">
                    <input type="radio" name="type" value="dejar" {{ old('type') === 'dejar' ? 'checked' : '' }}>
                    <span class="tipo-icono">🚫</span>
                    <span class="tipo-texto">
                        <strong>Quiero dejar</strong>
                        <small>Fumar, el alcohol...</small>
                    </span>
                </label>
            </div>
            @error('type')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- frecuencia semanal (solo para tipo "hacer") --}}
        <div class="campo-grupo" id="campo-frecuencia" style="display: none;">
            <label for="target_per_week" class="form-label-levlup">¿Cuántos días a la semana?</label>
            <div class="frecuencia-selector">
                @for($i = 1; $i <= 7; $i++)
                    <label class="frecuencia-opcion {{ old('target_per_week') == $i ? 'activo' : '' }}">
                    <input type="radio" name="target_per_week" value="{{ $i }}" {{ old('target_per_week') == $i ? 'checked' : '' }}>
                    {{ $i }}
                    </label>
                    @endfor
            </div>
            @error('target_per_week')
            <span class="form-error">{{ $message }}</span>
            @enderror

            {{-- duración --}}
            <div class="campo-grupo mt-3" id="campo-duracion">
                <label for="duration_minutes" class="form-label-levlup">Duración <span class="texto-suave">(minutos, opcional)</span></label>
                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    class="form-input-levlup {{ $errors->has('duration_minutes') ? 'input-error' : '' }}"
                    value="{{ old('duration_minutes', $sugerencia?->suggested_duration_minutes) }}"
                    min="1"
                    max="480"
                    placeholder="Ej: 30">
                @error('duration_minutes')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- categoría --}}
            <div class="campo-grupo mt-3">
                <label for="category" class="form-label-levlup">Categoría <span class="texto-suave">(opcional)</span></label>
                <select id="category" name="category" class="form-input-levlup">
                    <option value="">Sin categoría</option>
                    @foreach(['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad', 'aprendizaje', 'creatividad', 'sueno', 'social', 'finanzas', 'hogar', 'naturaleza'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $sugerencia?->category) === $cat ? 'selected' : '' }}>
                        {{ ucfirst($cat) }}
                    </option>
                    @endforeach
                </select>
                @error('category')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

        </div>

        {{-- duración (solo para tipo "hacer") --}}
        <div class="campo-grupo" id="campo-duracion" style="display: none;">
            <label for="duration_minutes" class="form-label-levlup">Duración <span class="texto-suave">(minutos, opcional)</span></label>
            <input
                type="number"
                id="duration_minutes"
                name="duration_minutes"
                class="form-input-levlup {{ $errors->has('duration_minutes') ? 'input-error' : '' }}"
                value="{{ old('duration_minutes', $sugerencia?->suggested_duration_minutes) }}"
                min="1"
                max="480"
                placeholder="Ej: 30">
            @error('duration_minutes')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primario">⭐ Crear hábito</button>

    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/habitos.js') }}"></script>
@endpush