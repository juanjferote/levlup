@extends('layouts.main-layout')

@section('titulo', 'Hábitos')

@section('contenido')

    <div class="pagina-titulo">
        <h2>Mis Hábitos</h2>
        <a href="{{ route('habitos.create') }}" class="btn-primario">+ Nuevo hábito</a>
    </div>

    {{-- pestañas --}}
    <div class="pestanas">
        <button class="pestana activa" data-pestana="pendientes">
            Pendientes
            @if($habitosHacer->isNotEmpty())
                <span class="pestana-contador">{{ $habitosHacer->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="dejar">
            Dejando atrás
            @if($habitosDejar->isNotEmpty())
                <span class="pestana-contador">{{ $habitosDejar->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="completados-hoy">
            Completados hoy
            @if($habitosRegistrados->isNotEmpty())
                <span class="pestana-contador verde">{{ $habitosRegistrados->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="objetivo-cumplido">
            Objetivo cumplido
            @if($habitosCompletados->isNotEmpty())
                <span class="pestana-contador verde">{{ $habitosCompletados->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="archivados">
            Archivados
            @if($habitosArchivados->isNotEmpty())
                <span class="pestana-contador">{{ $habitosArchivados->count() }}</span>
            @endif
        </button>
    </div>

    {{-- pendientes --}}
    <div class="pestana-contenido pestana-contenido-grande activo" id="pestana-pendientes">
        <div class="bloque">
            <h3 class="bloque-titulo">// Hábitos pendientes</h3>
            @forelse($habitosHacer as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => false])
            @empty
                <p class="texto-suave">No tienes hábitos pendientes. ¡Buen trabajo!</p>
            @endforelse
        </div>
    </div>

    {{-- dejando atrás --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-dejar">
        <div class="bloque">
            <h3 class="bloque-titulo">// Dejando atrás</h3>
            @forelse($habitosDejar as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => false])
            @empty
                <p class="texto-suave">No tienes hábitos de dejar activos.</p>
            @endforelse
        </div>
    </div>

    {{-- completados hoy --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-completados-hoy">
        <div class="bloque">
            <h3 class="bloque-titulo">// Completados hoy</h3>
            @forelse($habitosRegistrados as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => true])
            @empty
                <p class="texto-suave">Aún no has registrado ningún hábito hoy.</p>
            @endforelse
        </div>
    </div>

    {{-- objetivo semanal cumplido --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-objetivo-cumplido">
        <div class="bloque">
            <h3 class="bloque-titulo">// Objetivo semanal cumplido</h3>
            @forelse($habitosCompletados as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => true])
            @empty
                <p class="texto-suave">Aún no has cumplido el objetivo semanal de ningún hábito.</p>
            @endforelse
        </div>
    </div>

    {{-- archivados --}}
    <div class="pestana-contenido pestana-contenido-grande" id="pestana-archivados">
        <div class="bloque">
            <h3 class="bloque-titulo">// Hábitos archivados</h3>
            @forelse($habitosArchivados as $habito)
                @include('habitos._habito', ['habito' => $habito, 'completado' => false])
            @empty
                <p class="texto-suave">No tienes hábitos archivados.</p>
            @endforelse
        </div>
    </div>

@endsection