@extends('layouts.main-layout')

@section('titulo', 'Insignias')

@section('contenido')

<div class="pagina-titulo">
    <h2>Insignias</h2>
</div>

{{-- fila 1: misiones + constancia --}}
<div class="insignias-fila">

    <div class="bloque">
        <h3 class="bloque-titulo">// Misiones <span class="bloque-contador">({{ $tareas->where('desbloqueada', true)->count() }}/{{ $tareas->count() }})</span></h3>
        @php $pct = $tareas->count() > 0 ? round($tareas->where('desbloqueada', true)->count() / $tareas->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
        <div class="insignias-grid">
            @foreach($tareas as $insignia)
                @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Hábitos — Constancia <span class="bloque-contador">({{ $hacer->where('desbloqueada', true)->count() }}/{{ $hacer->count() }})</span></h3>
        @php $pct = $hacer->count() > 0 ? round($hacer->where('desbloqueada', true)->count() / $hacer->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
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
        <h3 class="bloque-titulo">// Hábitos — Resistencia <span class="bloque-contador">({{ $dejar->where('desbloqueada', true)->count() }}/{{ $dejar->count() }})</span></h3>
        @php $pct = $dejar->count() > 0 ? round($dejar->where('desbloqueada', true)->count() / $dejar->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
        <div class="insignias-grid">
            @foreach($dejar as $insignia)
                @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Diversidad <span class="bloque-contador">({{ $diversidad->where('desbloqueada', true)->count() }}/{{ $diversidad->count() }})</span></h3>
        @php $pct = $diversidad->count() > 0 ? round($diversidad->where('desbloqueada', true)->count() / $diversidad->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
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
        <h3 class="bloque-titulo">// Explorador <span class="bloque-contador">({{ $intereses->where('desbloqueada', true)->count() }}/{{ $intereses->count() }})</span></h3>
        @php $pct = $intereses->count() > 0 ? round($intereses->where('desbloqueada', true)->count() / $intereses->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
        <div class="insignias-grid">
            @foreach($intereses as $insignia)
                @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

    <div class="bloque">
        <h3 class="bloque-titulo">// Rachas <span class="bloque-contador">({{ $rachas->where('desbloqueada', true)->count() }}/{{ $rachas->count() }})</span></h3>
        @php $pct = $rachas->count() > 0 ? round($rachas->where('desbloqueada', true)->count() / $rachas->count() * 100) : 0; @endphp
        <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
        <div class="insignias-grid">
            @foreach($rachas as $insignia)
                @include('insignias._insignia', ['insignia' => $insignia])
            @endforeach
        </div>
    </div>

</div>

{{-- hábitos por categoría: de dos en dos --}}
@php $categoriasArray = $categorias->toArray(); $keys = array_keys($categoriasArray); @endphp

@for($i = 0; $i < count($keys); $i += 2)
    <div class="insignias-fila mt-3">

        <div class="bloque">
            @php
                $col1 = collect($categorias[$keys[$i]]);
                $pct = $col1->count() > 0 ? round($col1->where('desbloqueada', true)->count() / $col1->count() * 100) : 0;
            @endphp
            <h3 class="bloque-titulo">// {{ ucfirst($keys[$i]) }} <span class="bloque-contador">({{ $col1->where('desbloqueada', true)->count() }}/{{ $col1->count() }})</span></h3>
            <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
            <div class="insignias-grid">
                @foreach($categorias[$keys[$i]] as $insignia)
                    @include('insignias._insignia', ['insignia' => $insignia])
                @endforeach
            </div>
        </div>

        @if(isset($keys[$i + 1]))
        <div class="bloque">
            @php
                $col2 = collect($categorias[$keys[$i + 1]]);
                $pct = $col2->count() > 0 ? round($col2->where('desbloqueada', true)->count() / $col2->count() * 100) : 0;
            @endphp
            <h3 class="bloque-titulo">// {{ ucfirst($keys[$i + 1]) }} <span class="bloque-contador">({{ $col2->where('desbloqueada', true)->count() }}/{{ $col2->count() }})</span></h3>
            <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
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
    @php $pct = $niveles->count() > 0 ? round($niveles->where('desbloqueada', true)->count() / $niveles->count() * 100) : 0; @endphp
    <h3 class="bloque-titulo">// Niveles <span class="bloque-contador">({{ $niveles->where('desbloqueada', true)->count() }}/{{ $niveles->count() }})</span></h3>
    <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
    <div class="insignias-grid">
        @foreach($niveles as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
        @endforeach
    </div>
</div>

{{-- insignias especiales --}}
@if($especiales->isNotEmpty())
<div class="bloque mt-3">
    @php $pct = $especiales->count() > 0 ? round($especiales->where('desbloqueada', true)->count() / $especiales->count() * 100) : 0; @endphp
    <h3 class="bloque-titulo">// Especiales <span class="bloque-contador">({{ $especiales->where('desbloqueada', true)->count() }}/{{ $especiales->count() }})</span></h3>
    <div class="insignia-progreso"><div class="insignia-progreso__barra" style="width: {{ $pct }}%"></div></div>
    <div class="insignias-grid">
        @foreach($especiales as $insignia)
            @include('insignias._insignia', ['insignia' => $insignia])
        @endforeach
    </div>
</div>
@endif

@endsection