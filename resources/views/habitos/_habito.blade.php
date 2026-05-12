<div class="item-card {{ $completado ? 'completada' : '' }}">

    <div class="item-info">
        <span class="item-titulo">{{ $habito->title }}</span>

        @if($habito->description)
        <span class="item-descripcion">{{ $habito->description }}</span>
        @endif

        <div class="habito-meta">
            @if($habito->type === 'hacer')
            @php
            $logsEstaSemana = $habito->logs_esta_semana ?? 0;
            $porcentaje = min(100, round(($logsEstaSemana / $habito->target_per_week) * 100));
            @endphp
            <span class="habito-badge">🎯 {{ $logsEstaSemana }}/{{ $habito->target_per_week }}</span>
            @else
            @if(!$completado)
            <span class="habito-badge">🚫 {{ $habito->racha_dias ?? 0 }} {{ ($habito->racha_dias ?? 0) === 1 ? 'día' : 'días' }} sin fallar</span>
            @endif
            @if($habito->type === 'dejar')
            <span class="habito-badge">📅 Desde {{ $habito->created_at->format('d/m/Y H:i') }}</span>
            @endif
            @endif

            @if($habito->category)
            <span class="habito-badge">{{ ucfirst($habito->category) }}</span>
            @endif
        </div>
    </div>

    <div class="item-acciones">

        @if(!$habito->active)
        {{-- hábito archivado: solo mostrar botón de recuperar --}}
        @if(empty($modoResumen))
        <form action="{{ route('habitos.recuperar', $habito) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-secundario btn-pequeño">↩ Recuperar</button>
        </form>
        @endif
        @else
        {{-- hábito activo: acciones normales --}}
        @if($completado)
        <span class="badge-completada">✔ Hecho hoy</span>
        @else
        <form action="{{ route('habitos.registrar', $habito) }}" method="POST">
            @csrf
            @method('PATCH')
            @if(!empty($modoResumen))
            <input type="hidden" name="origen" value="dashboard">
            @endif
            <button type="submit" class="btn-primario btn-pequeño">
                {{ $habito->type === 'hacer' ? '✔ Registrar' : '✗ He fallado hoy' }}
            </button>
        </form>
        @endif

        @if(empty($modoResumen))
        <a href="{{ route('habitos.edit', $habito) }}" class="btn-secundario btn-pequeño">— Editar</a>
        <form action="{{ route('habitos.destroy', $habito) }}" method="POST"
            onsubmit="return confirm('¿Archivar este hábito?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-peligro btn-pequeño">Archivar</button>
        </form>
        @endif
        @endif

    </div>

</div>