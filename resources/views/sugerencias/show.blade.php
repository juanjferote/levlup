@extends('layouts.main-layout')

@section('titulo', $sugerencia->title)

@section('contenido')

    <div class="pagina-titulo">
        <h2>💡 {{ $sugerencia->title }}</h2>
        <a href="{{ route('sugerencias.index') }}" class="btn-secundario">← Volver</a>
    </div>

    <div class="bloque bloque-formulario">

        {{-- descripción --}}
        @if($sugerencia->description)
            <p class="texto-suave mb-3">{{ $sugerencia->description }}</p>
        @endif

        {{-- detalles --}}
        <div class="sugerencia-meta mb-4">
            <span class="habito-badge">🎯 {{ $sugerencia->suggested_target_per_week }}x semana</span>

            @if($sugerencia->suggested_duration_minutes)
                <span class="habito-badge">⏱ {{ $sugerencia->suggested_duration_minutes }} min</span>
            @endif

            <span class="habito-badge">{{ str_repeat('⭐', $sugerencia->difficulty_level) }}</span>
            <span class="habito-badge">📂 {{ ucfirst($sugerencia->category) }}</span>
        </div>

        {{-- confirmación --}}
        <div class="sugerencia-confirmacion">
            <p class="texto-suave">¿Quieres añadir este hábito a tu lista?</p>

            <div class="sugerencia-confirmacion-acciones">
                <form action="{{ route('sugerencias.añadir', $sugerencia) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primario">⭐ Añadir a mis hábitos</button>
                </form>

                <a href="{{ route('sugerencias.index') }}" class="btn-secundario">Cancelar</a>
            </div>
        </div>

    </div>

@endsection