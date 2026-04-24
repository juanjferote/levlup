<div class="habito-card">

    <div class="habito-info">
        <span class="habito-titulo">{{ $habito->title }}</span>

        @if($habito->description)
            <span class="habito-descripcion">{{ $habito->description }}</span>
        @endif

        <div class="habito-meta">
            @if($habito->type === 'hacer')
                <span class="habito-badge">🎯 {{ $habito->target_per_week }}x semana</span>
            @else
                <span class="habito-badge">🚫 Dejando</span>
            @endif

            @if($habito->category)
                <span class="habito-badge">{{ $habito->category }}</span>
            @endif
        </div>
    </div>

    <div class="habito-acciones">

        {{-- registrar cumplimiento --}}
        @php
            $registradoHoy = $habito->logs()->whereDate('logged_date', today())->exists();
        @endphp

        @if($registradoHoy)
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

        {{-- editar --}}
        <a href="{{ route('habitos.edit', $habito) }}" class="btn-secundario btn-pequeño">✏ Editar</a>

        {{-- archivar --}}
        <form action="{{ route('habitos.destroy', $habito) }}" method="POST"
              onsubmit="return confirm('¿Archivar este hábito?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-peligro btn-pequeño">📦 Archivar</button>
        </form>

    </div>

</div>