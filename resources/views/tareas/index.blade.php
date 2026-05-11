@extends('layouts.main-layout')

@section('titulo', 'Misiones')

@section('contenido')

    <div class="pagina-titulo">
        <h2>Mis Misiones</h2>
        <a href="{{ route('tareas.create') }}" class="btn-primario">+ Nueva misión</a>
    </div>

    {{-- pestañas --}}
    <div class="pestanas">
        <button class="pestana activa" data-pestana="hoy">
            Hoy
            @if($tareasHoy->isNotEmpty())
                <span class="pestana-contador">{{ $tareasHoy->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="vencidas">
            Vencidas
            @if($tareasVencidas->isNotEmpty())
                <span class="pestana-contador rojo">{{ $tareasVencidas->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="proximas">
            Próximas
            @if($tareasFuturas->isNotEmpty())
                <span class="pestana-contador">{{ $tareasFuturas->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="completadas">
            Completadas
            @if($tareasCompletadas->isNotEmpty())
                <span class="pestana-contador verde">{{ $tareasCompletadas->count() }}</span>
            @endif
        </button>
    </div>

    {{-- tareas de hoy --}}
    <div class="pestana-contenido pestana-contenido-grande activo" id="pestana-hoy">
        <div class="bloque">
            <h3 class="bloque-titulo">// Misiones de hoy</h3>
            @forelse($tareasHoy as $tarea)
                @include('tareas._tarea', ['tarea' => $tarea])
            @empty
                <p class="texto-suave">No tienes misiones para hoy. ¡Crea una!</p>
            @endforelse
        </div>
    </div>

    {{-- tareas vencidas --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-vencidas">
        <div class="bloque">
            <h3 class="bloque-titulo rojo">// Misiones vencidas</h3>
            @forelse($tareasVencidas as $tarea)
                @include('tareas._tarea', ['tarea' => $tarea])
            @empty
                <p class="texto-suave">No tienes misiones vencidas. ¡Al día! ✅</p>
            @endforelse
        </div>
    </div>

    {{-- tareas futuras --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-proximas">
        <div class="bloque">
            <h3 class="bloque-titulo">// Próximas misiones</h3>
            @forelse($tareasFuturas as $tarea)
                @include('tareas._tarea', ['tarea' => $tarea])
            @empty
                <p class="texto-suave">No tienes misiones programadas.</p>
            @endforelse
        </div>
    </div>

    {{-- tareas completadas --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-completadas">
        <div class="bloque">
            <h3 class="bloque-titulo verde">// Misiones completadas</h3>
            @forelse($tareasCompletadas as $tarea)
                @include('tareas._tarea', ['tarea' => $tarea])
            @empty
                <p class="texto-suave">Aún no has completado ninguna misión.</p>
            @endforelse
        </div>
    </div>

@endsection