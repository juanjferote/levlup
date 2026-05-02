@extends('layouts.main-layout')

@section('titulo', 'Dashboard')

@section('contenido')

<div class="pagina-titulo">
    <h2>Panel de control</h2>
</div>

{{-- Stats fila --}}
<div class="stats-fila">

    <div class="stat-card">
        <span class="stat-valor amarillo">{{ $puntos }}</span>
        <span class="stat-label">⭐ Puntos de experiencia</span>
    </div>

    <div class="stat-card">
        <span class="stat-valor amarillo">{{ $nivel }}</span>
        <span class="stat-label">🎮 Nivel</span>
    </div>

    <div class="stat-card">
        <span class="stat-valor naranja">{{ $racha }}</span>
        <span class="stat-label">🔥 Días de racha</span>
    </div>

    <div class="stat-card">
        <span class="stat-valor amarillo">{{ $tareasHoy }}</span>
        <span class="stat-label">✅ Tareas hoy</span>
    </div>

</div>

{{-- Fila principal --}}
<div class="dashboard-fila">

    {{-- misiones de hoy --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Misiones de hoy</h3>

        @forelse($tareasHoyLista as $tarea)
        @include('tareas._tarea', ['tarea' => $tarea, 'modoResumen' => true])
        @empty
        <p class="texto-suave">No tienes misiones para hoy.</p>
        @endforelse

        @if($tareasProximas->isNotEmpty())
        <h3 class="bloque-titulo mt-3">// Próximas misiones</h3>
        @foreach($tareasProximas as $tarea)
        @include('tareas._tarea', ['tarea' => $tarea, 'modoResumen' => true])
        @endforeach
        @endif

        <a href="{{ route('tareas.index') }}" class="btn-secundario btn-pequeño mt-3">
            Ver todas las misiones →
        </a>
    </div>

    {{-- hábitos de hoy --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Hábitos de hoy</h3>

        @if($habitosHacer->isEmpty() && $habitosDejar->isEmpty())
        <p class="texto-suave">No hay hábitos activos. ¡Empieza creando uno!</p>
        @else
        @foreach($habitosHacer as $habito)
        @include('habitos._habito', ['habito' => $habito, 'completado' => false, 'modoResumen' => true])
        @endforeach

        @foreach($habitosDejar as $habito)
        @include('habitos._habito', ['habito' => $habito, 'completado' => false, 'modoResumen' => true])
        @endforeach
        @endif

        <a href="{{ route('habitos.index') }}" class="btn-secundario btn-pequeño mt-3">
            Ver todos los hábitos →
        </a>
    </div>

</div>

<div class="dashboard-fila mt-3">
    {{-- Sugerencia del día --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Sugerencia del día</h3>
        @if($sugerenciaDelDia)
        <p class="item-titulo">{{ $sugerenciaDelDia->title }}</p>
        <p class="texto-suave">{{ $sugerenciaDelDia->description }}</p>
        <form method="POST" action="{{ route('sugerencias.añadir', $sugerenciaDelDia) }}">
            @csrf
            <button type="submit" class="btn-secundario btn-pequeño mt-3">
                + Añadir hábito
            </button>
        </form>
        @else
        <p class="texto-suave">No hay sugerencias disponibles.</p>
        @endif
    </div>

    {{-- Insignias recientes --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Insignias recientes</h3>

        <div class="insignias-grid">
            @forelse($insigniasRecientes as $userBadge)
            @php $userBadge->badge->desbloqueada = true; @endphp
            @include('insignias._insignia', ['insignia' => $userBadge->badge])
            @empty
            <p class="texto-suave">Aún no has conseguido ninguna insignia.</p>
            @endforelse
        </div>

        <a href="{{ route('insignias.index') }}" class="btn-secundario btn-pequeño mt-3">
            Ver todas las insignias →
        </a>
    </div>
</div>

{{-- Frase del día --}}
<p class="frase-separador">❝ {{ $fraseDelDia['texto'] }} ❞ <span class="frase-autor">— {{ $fraseDelDia['autor'] }}</span></p>

</div>

@endsection