<div class="item-card tarea-card {{ $tarea->completed ? 'completada' : '' }}">

    <div class="item-info">
        <span class="item-titulo">{{ $tarea->title }}</span>

        @if($tarea->description)
        <span class="item-descripcion">{{ $tarea->description }}</span>
        @endif

        <span class="tarea-fecha">
            🗓 {{ $tarea->scheduled_at->format('d/m/Y') }}
            @if($tarea->scheduled_at->format('H:i') !== '00:00')
            · {{ $tarea->scheduled_at->format('H:i') }}
            @endif
        </span>
    </div>

    <div class="item-acciones">
        @if(!$tarea->completed)
            @if(!$tarea->scheduled_at->isFuture() || $tarea->scheduled_at->isToday())
            <form action="{{ route('tareas.completar', $tarea) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-primario btn-pequeño">✔ Completar</button>
            </form>
            @endif

            @if(empty($modoResumen))
            <a href="{{ route('tareas.edit', $tarea) }}" class="btn-secundario btn-pequeño">Editar</a>
            <form action="{{ route('tareas.destroy', $tarea) }}" method="POST"
                onsubmit="return confirm('¿Seguro que quieres eliminar esta misión?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-peligro btn-pequeño">Eliminar</button>
            </form>
            @endif
        @else
            <span class="badge-completada">✔ Completada</span>
        @endif
    </div>

</div>