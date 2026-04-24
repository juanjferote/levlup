@extends('layouts.main-layout')

@section('titulo', 'Dashboard')

@section('contenido')

    <div class="pagina-titulo">
        <h2>📋 Panel de control</h2>
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
                @include('tareas._tarea', ['tarea' => $tarea])
            @empty
                <p class="texto-suave">No tienes misiones para hoy.</p>
            @endforelse

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
                    @include('habitos._habito', ['habito' => $habito])
                @endforeach

                @foreach($habitosDejar as $habito)
                    @include('habitos._habito', ['habito' => $habito])
                @endforeach
            @endif

            <a href="{{ route('habitos.index') }}" class="btn-secundario btn-pequeño mt-3">
                Ver todos los hábitos →
            </a>
        </div>

    </div>

    {{-- Fila secundaria --}}
    <div class="dashboard-fila mt-3">

        <div class="bloque">
            <h3 class="bloque-titulo">// Mensaje del día</h3>
            <p class="texto-suave">¡Bienvenido a LevlUp! Empieza añadiendo tus primeras tareas y hábitos.</p>
        </div>

        <div class="bloque">
            <h3 class="bloque-titulo">// Insignias recientes</h3>
            <p class="texto-suave">Aún no has conseguido ninguna insignia.</p>
        </div>

    </div>

@endsection