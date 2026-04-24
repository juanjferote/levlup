<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Levlup — @yield('titulo', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

@stack('scripts')

<body>

    @include('layouts.header')

    <div class="layout-principal">

        <aside class="sidebar">

            {{-- Jugador --}}
            <div class="sidebar-jugador">
                <div class="jugador-avatar">
                    <img class="contenedor-avatar"
                        src="{{ auth()->user()->avatarUrl() }}"
                        alt="avatar de {{ auth()->user()->name }}">
                </div>
                <div class="jugador-info">
                    <span class="jugador-nombre">{{ auth()->user()->name ?? 'Jugador' }}</span>
                    <span class="jugador-nivel">Nivel {{ auth()->user()->level }}</span>
                </div>
            </div>

            {{-- Barra de XP --}}
            <div class="sidebar-xp">
                <div class="xp-barra-fondo">
                    <div class="xp-barra-relleno" style="width: {{ $xpPorcentaje ?? 0 }}%"></div>
                </div>
                <span class="xp-texto">
                    @if(auth()->user()->level >= 10)
                    ⭐ Nivel máximo
                    @else
                    {{ auth()->user()->pointsToNextLevel() }} XP para nivel {{ auth()->user()->level + 1 }}
                    @endif
                </span>
            </div>

            {{-- Navegación --}}
            <nav class="sidebar-nav">
                <a href="{{ route('tareas.index') }}"
                    class="nav-item {{ request()->routeIs('tareas.*') ? 'activo' : '' }}">
                    📋 Misiones
                </a>
                <a href="{{ route('habitos.index') }}"
                    class="nav-item {{ request()->routeIs('habitos.*') ? 'activo' : '' }}">
                    🔄 Hábitos
                </a>
                <a href="#"
                    class="nav-item {{ request()->routeIs('habitos.sugerencias') ? 'activo' : '' }}">
                    💡 Sugerencias
                </a>
                <a href="#"
                    class="nav-item {{ request()->routeIs('estadisticas.*') ? 'activo' : '' }}">
                    📊 Estadísticas
                </a>
                <a href="#"
                    class="nav-item {{ request()->routeIs('insignias.*') ? 'activo' : '' }}">
                    🏆 Insignias
                </a>
            </nav>

        </aside>

        <main class="contenido-principal">
            @yield('contenido')
        </main>

    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/tareas.js') }}"></script>

</body>

</html>