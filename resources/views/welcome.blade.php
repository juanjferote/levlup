<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LevlUp — Sube de nivel cada día</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body class="landing-body">

    {{-- Header --}}
    <header class="landing-header">
        <span class="landing-logo">⚔ LevlUp</span>
        <nav class="landing-nav">
            @auth
            <a href="{{ route('dashboard') }}" class="btn-primario">Ir al dashboard</a>
            @else
            <a href="{{ route('login') }}" class="btn-secundario">Entrar</a>
            <a href="{{ route('register') }}" class="btn-primario">Registrarse</a>
            @endauth
        </nav>
    </header>

    <main class="landing-main">

        {{-- Hero --}}
        <section class="hero">
            <p class="hero-tag">// Tu aventura empieza aquí</p>
            <h1 class="hero-titulo">Sube de <span>nivel</span><br>cada día</h1>
            <p class="hero-subtitulo">
                Gestiona tus tareas, construye hábitos y desbloquea logros.
                LevlUp convierte tu rutina diaria en una partida que merece la pena jugar.
            </p>
            <div class="hero-acciones">
                <a href="{{ route('register') }}" class="btn-primario">Empezar gratis</a>
                <a href="{{ route('login') }}" class="btn-secundario">Ya tengo cuenta</a>
            </div>
            <div class="hero-avatares">
                @foreach(['warrior','mage','archer','ninja','knight','rogue','healer','bard'] as $seed)
                <div class="hero-avatar">
                    <img
                        src="https://api.dicebear.com/9.x/pixel-art/svg?seed={{ $seed }}&size=48"
                        alt="avatar">
                </div>
                @endforeach
            </div>
            <p class="hero-avatar-texto">Elige tu personaje y empieza tu aventura</p>
        </section>

        {{-- Features --}}
        <section class="landing-seccion">
            <p class="seccion-tag">// Funcionalidades</p>
            <h2 class="seccion-titulo">Todo lo que necesitas</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icono">📋</span>
                    <div class="feature-titulo">Gestión de tareas</div>
                    <p class="feature-desc">
                        Crea, organiza y completa tus tareas diarias con fecha y hora.
                        Visualiza todo en tu calendario personal.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icono">🔄</span>
                    <div class="feature-titulo">Seguimiento de hábitos</div>
                    <p class="feature-desc">
                        Registra hábitos diarios o semanales y mantén tu racha activa.
                        La aplicación calcula tu progreso automáticamente.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icono">🎮</span>
                    <div class="feature-titulo">Sistema de niveles</div>
                    <p class="feature-desc">
                        Gana XP completando tareas y hábitos. Sube de nivel y desbloquea
                        sugerencias más avanzadas adaptadas a tu progreso.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icono">🏆</span>
                    <div class="feature-titulo">Insignias y logros</div>
                    <p class="feature-desc">
                        Desbloquea insignias por tus logros. Cada hito tiene su
                        recompensa. ¿Cuántas puedes conseguir?
                    </p>
                </div>
            </div>
        </section>

        {{-- Cómo funciona --}}
        <section class="landing-seccion">
            <p class="seccion-tag">// Cómo funciona</p>
            <h2 class="seccion-titulo">Tres pasos para empezar</h2>
            <div class="pasos-grid">
                <div class="paso-card">
                    <span class="paso-numero">01</span>
                    <div class="paso-titulo">Crea tu perfil</div>
                    <p class="paso-desc">
                        Regístrate, elige tu personaje y selecciona tus intereses.
                        LevlUp te sugerirá hábitos personalizados desde el primer día.
                    </p>
                </div>
                <div class="paso-card">
                    <span class="paso-numero">02</span>
                    <div class="paso-titulo">Define tus objetivos</div>
                    <p class="paso-desc">
                        Añade tus tareas y activa los hábitos que quieres trabajar.
                        Tú decides el ritmo, la aplicación se adapta a ti.
                    </p>
                </div>
                <div class="paso-card">
                    <span class="paso-numero">03</span>
                    <div class="paso-titulo">Sube de nivel</div>
                    <p class="paso-desc">
                        Completa tareas, mantén tus rachas y gana XP cada día.
                        Cuanto más constante seas, más lejos llegarás.
                    </p>
                </div>
            </div>
        </section>

        {{-- CTA final --}}
        <section class="cta-final">
            <h2 class="cta-titulo">¿Listo para <span>subir de nivel</span>?</h2>
            <p class="cta-subtitulo">
                Únete a LevlUp y convierte tus objetivos en logros reales.
                Gratis, sin complicaciones.
            </p>
            <div class="cta-acciones">
                <a href="{{ route('register') }}" class="btn-primario">Empezar ahora</a>
                <a href="{{ route('login') }}" class="btn-secundario">Ya tengo cuenta</a>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="landing-footer">
        <span class="landing-footer-texto">⚔ LEVLUP — Juan José Fernández Otero</span>
        <span class="landing-footer-texto">v1.0</span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>