@extends('layouts.main-layout')

@section('titulo', 'Estadísticas')

@section('contenido')

    <div class="pagina-titulo">
        <h2>📊 Estadísticas</h2>
    </div>

    {{-- fila 1: misiones + progreso --}}
    <div class="stats-seccion-fila">

        {{-- misiones --}}
        <div class="bloque">
            <h3 class="bloque-titulo">// Misiones</h3>

            <div class="stat-grupo">
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $misionesTotalCompletadas }}</span>
                    <span class="stat-etiqueta">Misiones completadas</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $misionesEstaSemana }}</span>
                    <span class="stat-etiqueta">Completadas esta semana</span>
                </div>
            </div>
        </div>

        {{-- progreso --}}
        <div class="bloque">
            <h3 class="bloque-titulo">// Progreso</h3>

            <div class="stat-grupo">
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $progresoNivel }}</span>
                    <span class="stat-etiqueta">Nivel actual</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $progresoPuntos }}</span>
                    <span class="stat-etiqueta">Puntos totales</span>
                </div>
            </div>

            {{-- barra de progreso hacia el siguiente nivel --}}
            @if($progresoNivel < 10)
                <div class="stat-barra-grupo mt-3">
                    <div class="stat-barra-cabecera">
                        <span class="stat-barra-label">Nivel {{ $progresoNivel }} → {{ $progresoNivel + 1 }}</span>
                        <span class="stat-barra-label">{{ $progresoPorcentaje }}%</span>
                    </div>
                    <div class="stat-barra-fondo">
                        <div class="stat-barra-relleno" style="width: {{ $progresoPorcentaje }}%"></div>
                    </div>
                    <span class="texto-suave">Faltan {{ $progresoFaltan }} XP para el siguiente nivel</span>
                </div>
            @else
                <p class="texto-suave mt-3">⭐ Nivel máximo alcanzado</p>
            @endif
        </div>

    </div>

    {{-- fila 2: hábitos --}}
    <div class="stats-seccion-fila mt-3">

        <div class="bloque">
            <h3 class="bloque-titulo">// Hábitos</h3>

            <div class="stat-grupo">
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $habitosTotalHacer }}</span>
                    <span class="stat-etiqueta">Hábitos de hacer</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $habitosTotalDejar }}</span>
                    <span class="stat-etiqueta">Hábitos de dejar</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero naranja">{{ $habitosMejorRacha }}</span>
                    <span class="stat-etiqueta">Mejor racha histórica</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $habitosCategoriaStar ?? '—' }}</span>
                    <span class="stat-etiqueta">Categoría más trabajada</span>
                </div>
            </div>

            {{-- tasa de cumplimiento semanal --}}
            <div class="stat-barra-grupo mt-3">
                <div class="stat-barra-cabecera">
                    <span class="stat-barra-label">Cumplimiento semanal</span>
                    <span class="stat-barra-label">{{ $habitosTasaSemanal }}%</span>
                </div>
                <div class="stat-barra-fondo">
                    <div class="stat-barra-relleno {{ $habitosTasaSemanal >= 80 ? 'barra-verde' : ($habitosTasaSemanal >= 50 ? 'barra-naranja' : 'barra-roja') }}"
                         style="width: {{ $habitosTasaSemanal }}%"></div>
                </div>
            </div>
        </div>

        {{-- insignias --}}
        <div class="bloque">
            <h3 class="bloque-titulo">// Insignias</h3>

            <div class="stat-grupo">
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $insigniasTotalDesbloqueadas }}</span>
                    <span class="stat-etiqueta">Desbloqueadas</span>
                </div>
                <div class="stat-grande">
                    <span class="stat-numero amarillo">{{ $insigniasTotalDisponibles }}</span>
                    <span class="stat-etiqueta">Total disponibles</span>
                </div>
            </div>

            {{-- barra de progreso insignias --}}
            <div class="stat-barra-grupo mt-3">
                <div class="stat-barra-cabecera">
                    <span class="stat-barra-label">Progreso de colección</span>
                    <span class="stat-barra-label">{{ $insigniasPorcentaje }}%</span>
                </div>
                <div class="stat-barra-fondo">
                    <div class="stat-barra-relleno" style="width: {{ $insigniasPorcentaje }}%"></div>
                </div>
            </div>

            {{-- últimas insignias desbloqueadas --}}
            @if($insigniasRecientes->isNotEmpty())
                <div class="mt-3">
                    <span class="form-label-levlup">Últimas desbloqueadas</span>
                    <div class="stat-insignias-recientes">
                        @foreach($insigniasRecientes as $userBadge)
                            <div class="stat-insignia-item">
                                <span class="stat-insignia-icono">{{ $userBadge->badge->icon }}</span>
                                <span class="stat-insignia-nombre">{{ $userBadge->badge->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection