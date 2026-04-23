<header class="levlup-header">

    <div class="header-logo">
        <a href="{{ route('dashboard') }}">⚔ LEVLUP</a>
    </div>

    <div class="header-acciones">

        {{-- nivel + puntos --}}
        <span class="header-xp">
            NV.{{ auth()->user()->level }} &nbsp;·&nbsp; ⭐ {{ auth()->user()->points }} XP
        </span>

        <span class="header-usuario">{{ auth()->user()->name ?? 'Jugador' }}</span>

        <button onclick="toggleModoNoche()" class="btn-icono" title="Modo noche">🌙</button>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-icono" title="Salir">🚪</button>
        </form>

    </div>

</header>