<header class="levlup-header">

    <div class="header-logo">
        <a href="{{ route('dashboard') }}">⚔ LEVLUP</a>
    </div>

    <div class="header-acciones">

        <span class="header-xp">⭐ {{ auth()->user()->puntos ?? 0 }} XP</span>

        <span class="header-usuario">{{ auth()->user()->name ?? 'Jugador' }}</span>

        <button onclick="toggleModoNoche()" class="btn-icono" title="Modo noche">
            🌙
        </button>


        {{-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-icono" title="Salir">
            🚪
        </button>
        </form> --}}    

    </div>

</header>