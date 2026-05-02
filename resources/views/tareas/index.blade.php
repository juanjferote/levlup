@extends('layouts.main-layout')

@section('titulo', 'Misiones')

@section('contenido')

    <div class="pagina-titulo">
        <h2>Mis Misiones</h2>
        <a href="{{ route('tareas.create') }}" class="btn-primario">+ Nueva misión</a>
    </div>

    {{-- tareas de hoy --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Misiones de hoy</h3>

        @forelse($tareasHoy as $tarea)
            @include('tareas._tarea', ['tarea' => $tarea])
        @empty
            <p class="texto-suave">No tienes misiones para hoy. ¡Crea una!</p>
        @endforelse
    </div>

    {{-- tareas vencidas --}}
    @if($tareasVencidas->isNotEmpty())
        <div class="bloque mt-3">
            <h3 class="bloque-titulo rojo">// Misiones vencidas</h3>

            @foreach($tareasVencidas as $tarea)
                @include('tareas._tarea', ['tarea' => $tarea])
            @endforeach
        </div>
    @endif

    {{-- tareas futuras --}}
    <div class="bloque mt-3">
        <h3 class="bloque-titulo">// Próximas misiones</h3>

        @forelse($tareasFuturas as $tarea)
            @include('tareas._tarea', ['tarea' => $tarea])
        @empty
            <p class="texto-suave">No tienes misiones programadas.</p>
        @endforelse
    </div>

@endsection