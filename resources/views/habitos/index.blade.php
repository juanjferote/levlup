@extends('layouts.main-layout')

@section('titulo', 'Hábitos')

@section('contenido')

<div class="pagina-titulo">
    <h2>Mis Hábitos</h2>
    <a href="{{ route('habitos.create') }}" class="btn-primario">+ Nuevo hábito</a>
</div>

{{-- pestañas --}}
<div class="pestanas-wrapper">
    <div class="pestanas pestanas--habitos">
        <button class="pestana activa" data-pestana="pendientes" data-movil="Pend.">
            Pendientes
            @if($habitosHacer->isNotEmpty())
            <span class="pestana-contador">{{ $habitosHacer->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="dejar" data-movil="Dejar">
            Dejando atrás
            @if($habitosDejar->isNotEmpty())
            <span class="pestana-contador">{{ $habitosDejar->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="completados-hoy" data-movil="Hoy">
            Completados hoy
            @if($habitosRegistrados->isNotEmpty())
            <span class="pestana-contador verde">{{ $habitosRegistrados->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="objetivo-cumplido" data-movil="Semana">
            Objetivo cumplido
            @if($habitosCompletados->isNotEmpty())
            <span class="pestana-contador verde">{{ $habitosCompletados->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="fallados" data-movil="Fallo">
            Fallados hoy
            @if($habitosFallados->isNotEmpty())
            <span class="pestana-contador rojo">{{ $habitosFallados->count() }}</span>
            @endif
        </button>
        <button class="pestana" data-pestana="archivados" data-movil="Arch.">
            Archivados
            @if($habitosArchivados->isNotEmpty())
            <span class="pestana-contador">{{ $habitosArchivados->count() }}</span>
            @endif
        </button>
    </div>
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

{{-- hábitos no cumplidos hoy --}}
<div class="pestana-contenido pestana-contenido-grande" id="pestana-fallados">
    <div class="bloque">
        <h3 class="bloque-titulo">// Fallados hoy</h3>
        @forelse($habitosFallados as $habito)
        <div class="item-card">
            <div class="item-info">
                <span class="item-titulo">{{ $habito->title }}</span>
                @if($habito->racha_anterior > 0)
                <span class="item-descripcion">
                    Llevabas <strong>{{ $habito->racha_anterior }} {{ $habito->racha_anterior === 1 ? 'día' : 'días' }}</strong> sin fallar. ¡Es un gran logro igualmente! Mañana puedes volver a empezar. 💪
                </span>
                @else
                <span class="item-descripcion">Has fallado el primer día. ¡Mañana es una nueva oportunidad! 💪</span>
                @endif
                @if($habito->category)
                <div class="habito-meta">
                    <span class="habito-badge">{{ ucfirst($habito->category) }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="texto-suave">No has fallado ningún hábito hoy. ¡Sigue así! 🎉</p>
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