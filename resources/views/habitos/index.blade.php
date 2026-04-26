@extends('layouts.main-layout')

@section('titulo', 'Hábitos')

@section('contenido')

    <div class="pagina-titulo">
        <h2>🔄 Mis Hábitos</h2>
        <a href="{{ route('habitos.create') }}" class="btn-primario">+ Nuevo hábito</a>
    </div>

    @if(session('exito'))
        <div class="alerta alerta-exito">{{ session('exito') }}</div>
    @endif

    @if(session('info'))
        <div class="alerta alerta-info">{{ session('info') }}</div>
    @endif

    {{-- hábitos de hacer pendientes --}}
    <div class="bloque">
        <h3 class="bloque-titulo">// Hábitos activos</h3>

        @forelse($habitosHacer as $habito)
            @include('habitos._habito', ['habito' => $habito, 'completado' => false])
        @empty
            <p class="texto-suave">No tienes hábitos pendientes. ¡Buen trabajo!</p>
        @endforelse
    </div>

    {{-- hábitos de dejar pendientes --}}
    @if($habitosDejar->isNotEmpty())
        <div class="bloque mt-3">
            <h3 class="bloque-titulo">// Dejando atrás</h3>

            @foreach($habitosDejar as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => false])
            @endforeach
        </div>
    @endif

    {{-- hábitos completados hoy --}}
    @if($habitosCompletados->isNotEmpty())
        <div class="bloque mt-3">
            <h3 class="bloque-titulo">// Completados hoy</h3>

            @foreach($habitosCompletados as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => true])
            @endforeach
        </div>
    @endif

@endsection