<header class="levlup-header">

    <div class="header-logo">
        <a href="{{ route('dashboard') }}">LEVLUP</a>
    </div>

    <div class="header-acciones">

        <span class="header-usuario">👤{{ auth()->user()->name ?? 'Jugador' }}</span>

        <button id="btn-modo" onclick="toggleModoNoche()" class="btn-icono" title="">🌙</button>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-icono" title="Salir">🚪</button>
        </form>

    </div>

</header>