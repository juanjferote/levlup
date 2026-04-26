<div class="habito-card {{ $completado ? 'completada' : '' }}">

    <div class="habito-info">
        <span class="habito-titulo">{{ $habito->title }}</span>

        @if($habito->description)
            <span class="habito-descripcion">{{ $habito->description }}</span>
        @endif

        <div class="habito-meta">
            @if($habito->type === 'hacer')
                @php
                    $logsEstaSemana = $habito->logs_esta_semana ?? 0;
                    $porcentaje     = min(100, round(($logsEstaSemana / $habito->target_per_week) * 100));
                @endphp
                <span class="habito-badge">🎯 {{ $logsEstaSemana }}/{{ $habito->target_per_week }}</span>
                <div class="habito-progreso-barra">
                    <div class="habito-progreso-relleno {{ $porcentaje >= 100 ? 'completo' : '' }}"
                         style="width: {{ $porcentaje }}%"></div>
                </div>
            @else
                <span class="habito-badge">🚫 Dejando</span>
            @endif

            @if($habito->category)
                <span class="habito-badge">{{ ucfirst($habito->category) }}</span>
            @endif
        </div>
    </div>

    <div class="habito-acciones">

        @if($completado)
            <span class="badge-completada">✔ Hecho hoy</span>
        @else
            <form action="{{ route('habitos.registrar', $habito) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-primario btn-pequeño">
                    {{ $habito->type === 'hacer' ? '✔ Registrar' : '💪 Sigo en ello' }}
                </button>
            </form>
        @endif

        <a href="{{ route('habitos.edit', $habito) }}" class="btn-secundario btn-pequeño">✏ Editar</a>

        <form action="{{ route('habitos.destroy', $habito) }}" method="POST"
              onsubmit="return confirm('¿Archivar este hábito?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-peligro btn-pequeño">📦 Archivar</button>
        </form>

    </div>

</div>