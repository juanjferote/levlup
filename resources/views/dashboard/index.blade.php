@extends('layouts.main-layout')

@section('titulo', 'Dashboard')

@section('contenido')

    <div class="pagina-titulo">
        <h2>📋 Panel de control</h2>
    </div>

    <div class="stats-fila">

        <div class="stat-card">
            <span class="stat-valor amarillo">{{ $puntos ?? 0 }}</span>
            <span class="stat-label">⭐ Puntos</span>
        </div>

        <div class="stat-card">
            <span class="stat-valor naranja">{{ $racha ?? 0 }}</span>
            <span class="stat-label">🔥 Días de racha</span>
        </div>

        <div class="stat-card">
            <span class="stat-valor amarillo">{{ $tareasHoy ?? 0 }}</span>
            <span class="stat-label">✅ Tareas hoy</span>
        </div>

        <div class="stat-card">
            <span class="stat-valor rojo">{{ $retosActivos ?? 0 }}</span>
            <span class="stat-label">⚔ Retos activos</span>
        </div>

    </div>

    <div class="dashboard-fila">

        <div class="bloque bloque-grande">
            <h3 class="bloque-titulo">// Misiones de hoy</h3>
            <p class="texto-suave">No hay misiones por ahora. ¡Crea tu primera tarea!</p>
        </div>

        <div class="bloque bloque-pequeño">
            <h3 class="bloque-titulo">// Insignias</h3>
            <p class="texto-suave">Aún no has conseguido ninguna insignia.</p>
        </div>

    </div>

@endsection