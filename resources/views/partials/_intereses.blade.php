{{-- partial reutilizable de intereses --}}
{{-- variables esperadas: $accion (ruta del form), $interesesActivos (array con los intereses actuales) --}}

<form method="POST" action="{{ $accion }}">
    @csrf

    @if ($errors->any())
        <p class="form-error">{{ $errors->first() }}</p>
    @endif

    {{-- intereses predefinidos --}}
    <div class="campo-grupo">
        <label class="form-label-levlup">Elige tus intereses</label>
        <div class="intereses-grid">
            @foreach (['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad', 'aprendizaje', 'creatividad', 'sueno', 'social', 'finanzas', 'hogar', 'naturaleza'] as $interes)
                <label class="interes-opcion {{ in_array($interes, $interesesActivos) ? 'activo' : '' }}">
                    <input
                        type="checkbox"
                        name="interests[]"
                        value="{{ $interes }}"
                        {{ in_array($interes, $interesesActivos) ? 'checked' : '' }}>
                    {{ ucfirst($interes) }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- intereses personalizados --}}
    <div class="campo-grupo">
        <label class="form-label-levlup">¿Tienes algún interés que no aparece? <span class="texto-suave">(opcional)</span></label>
        <div class="interes-personalizado-fila">
            <input
                type="text"
                id="interes-nuevo"
                class="form-input-levlup"
                placeholder="Ej: fotografía, cocina...">
            <button type="button" id="btn-añadir-interes" class="btn-secundario">+ Añadir</button>
        </div>
        <div id="intereses-personalizados" class="intereses-grid mt-2"></div>
    </div>

    <button type="submit" class="btn-primario">{{ $textBoton ?? 'Guardar intereses' }}</button>

</form>