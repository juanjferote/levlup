@extends('layouts.main-layout')

@section('titulo', 'Insignias')

@section('contenido')

<div class="pagina-titulo">
    <h2>🏆 Insignias</h2>
</div>

@if(session('exito'))
<div class="alerta alerta-exito">{{ session('exito') }}</div>
@endif

{{-- fila 1: misiones + constancia --}}
<div class="insignias-fila">

    <div class="bloque">
        <h3 class="bloque-titulo">// Misiones</h3>
        <div class="insignias-grid">
            @foreach($tareas as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Hábitos — Constancia</h3>
        <div class="insignias-grid">
            @foreach($hacer as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

</div>

{{-- fila 2: resistencia + diversidad --}}
<div class="insignias-fila mt-3">

    <div class="bloque">
        <h3 class="bloque-titulo">// Hábitos — Resistencia</h3>
        <div class="insignias-grid">
            @foreach($dejar as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Diversidad</h3>
        <div class="insignias-grid">
            @foreach($diversidad as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

</div>

{{-- fila 3: explorador + rachas --}}
<div class="insignias-fila mt-3">

    <div class="bloque">
        <h3 class="bloque-titulo">// Explorador</h3>
        <div class="insignias-grid">
            @foreach($intereses as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Rachas</h3>
        <div class="insignias-grid">
            @foreach($rachas as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

</div>

{{-- hábitos por categoría: de dos en dos --}}
@php $categoriasArray = $categorias->toArray(); $keys = array_keys($categoriasArray); @endphp

@for($i = 0; $i < count($keys); $i +=2)
    <div class="insignias-fila mt-3">

    <div class="bloque">
        <h3 class="bloque-titulo">// {{ ucfirst($keys[$i]) }}</h3>
        <div class="insignias-grid">
            @foreach($categorias[$keys[$i]] as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    @if(isset($keys[$i + 1]))
    <div class="bloque">
        <h3 class="bloque-titulo">// {{ ucfirst($keys[$i + 1]) }}</h3>
        <div class="insignias-grid">
            @foreach($categorias[$keys[$i + 1]] as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>
    @else
    <div></div>
    @endif

    </div>
    @endfor

    {{-- niveles al final --}}
    <div class="bloque mt-3">
        <h3 class="bloque-titulo">// Niveles</h3>
        <div class="insignias-grid">
            @foreach($niveles as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    @endsection