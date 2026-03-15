<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Levlup — @yield('titulo', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body>

    @include('layouts.header')

    <div class="layout-principal">

        <aside class="sidebar">
            <div class="sidebar-jugador">
                <div class="jugador-avatar">🧙</div>
                <div class="jugador-info">
                    <span class="jugador-nombre">{{ auth()->user()->name ?? 'Jugador' }}</span>
                    <span class="jugador-nivel">Nivel 1</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'activo' : '' }}">
                    📋 Misiones
                </a>
                <a href="#" class="nav-item {{ request()->routeIs('habitos.*') ? 'activo' : '' }}">
                    🔄 Hábitos
                </a>
                <a href="#" class="nav-item {{ request()->routeIs('retos.*') ? 'activo' : '' }}">
                    🏆 Retos
                </a>
                <a href="#" class="nav-item {{ request()->routeIs('estadisticas.*') ? 'activo' : '' }}">
                    📊 Estadísticas
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

</body>

</html>