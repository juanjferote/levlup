@extends('layouts.main-layout')

@section('titulo', 'Sugerencias')

@section('contenido')

<div class="pagina-titulo">
    <h2>💡 Sugerencias</h2>
</div>

@if(session('exito'))
<div class="alerta alerta-exito">{{ session('exito') }}</div>
@endif

@if(empty($sugerencias) || collect($sugerencias)->flatten()->isEmpty())
<div class="bloque">
    <p class="texto-suave">No hay sugerencias disponibles. Asegúrate de tener intereses configurados en tu perfil.</p>
    <a href="{{ route('profile.edit') }}" class="btn-primario mt-3">⚙️ Configurar intereses</a>
</div>
@else
@foreach($sugerencias as $categoria => $habitos)
@if($habitos->isNotEmpty())
<div class="bloque mt-3">
    <h3 class="bloque-titulo">// {{ ucfirst($categoria) }}</h3>

    @foreach($habitos as $habito)
    <div class="sugerencia-card">

        <div class="sugerencia-info">
            <span class="sugerencia-titulo">{{ $habito->title }}</span>

            @if($habito->description)
            <span class="sugerencia-descripcion">{{ $habito->description }}</span>
            @endif

            <div class="sugerencia-meta">
                <span class="habito-badge">🎯 {{ $habito->suggested_target_per_week }}x semana</span>

                @if($habito->suggested_duration_minutes)
                <span class="habito-badge">⏱ {{ $habito->suggested_duration_minutes }} min</span>
                @endif

                <span class="habito-badge dificultad-{{ $habito->difficulty_level }}">
                    {{ str_repeat('⭐', $habito->difficulty_level) }}
                </span>
            </div>
        </div>

        <a href="{{ route('sugerencias.show', $habito) }}" class="btn-primario btn-pequeño">
            + Añadir
        </a>

    </div>
    @endforeach

</div>
@endif
@endforeach
@endif

@endsection