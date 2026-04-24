// gestión del formulario de hábitos

document.addEventListener('DOMContentLoaded', function () {

    // ── Referencias a elementos del DOM ──
    const opcionesTipo      = document.querySelectorAll('input[name="type"]');
    const campoFrecuencia   = document.getElementById('campo-frecuencia');
    const opcionesTipoLabel = document.querySelectorAll('.tipo-opcion');

    // si no estamos en el formulario de hábitos, no hacemos nada
    if (!opcionesTipo.length) return;

    // ── Funciones ──

    // muestra u oculta el campo de frecuencia según el tipo seleccionado
    function actualizarFrecuencia() {
        const tipoSeleccionado = document.querySelector('input[name="type"]:checked');

        if (tipoSeleccionado && tipoSeleccionado.value === 'hacer') {
            campoFrecuencia.style.display = 'block';
        } else {
            campoFrecuencia.style.display = 'none';
        }
    }

    // marca visualmente la opción de tipo seleccionada
    function actualizarTipoActivo() {
        opcionesTipoLabel.forEach(function (label) {
            const input = label.querySelector('input[type="radio"]');
            if (input.checked) {
                label.classList.add('activo');
            } else {
                label.classList.remove('activo');
            }
        });
    }

    // marca visualmente la frecuencia seleccionada
    function actualizarFrecuenciaActiva() {
        document.querySelectorAll('.frecuencia-opcion').forEach(function (label) {
            const input = label.querySelector('input[type="radio"]');
            if (input.checked) {
                label.classList.add('activo');
            } else {
                label.classList.remove('activo');
            }
        });
    }

    // ── Eventos ──

    opcionesTipo.forEach(function (input) {
        input.addEventListener('change', function () {
            actualizarFrecuencia();
            actualizarTipoActivo();
        });
    });

    document.querySelectorAll('.frecuencia-opcion input').forEach(function (input) {
        input.addEventListener('change', actualizarFrecuenciaActiva);
    });

    // inicializamos el estado al cargar la página
    actualizarFrecuencia();
    actualizarTipoActivo();
    actualizarFrecuenciaActiva();

});