// gestión del formulario de fecha y hora de tareas

document.addEventListener('DOMContentLoaded', function () {

    // ── Referencias a elementos del DOM ──
    const btnHoy = document.querySelector('[data-opcion="hoy"]');
    const btnProgramar = document.querySelector('[data-opcion="programar"]');
    const campoFecha = document.getElementById('campo-fecha');
    const campoHora = document.getElementById('campo-hora');
    const inputFecha = document.getElementById('fecha');
    const inputHora = document.getElementById('hora');
    const inputHidden = document.getElementById('scheduled_at');
    const btnSubmit = document.getElementById('btn-submit');

    // si no estamos en el formulario de tareas, no hacemos nada
    if (!btnHoy || !btnProgramar) return;

    const hoy = inputFecha.dataset.hoy;

    // ── Funciones ──

    // construye el valor de scheduled_at combinando fecha y hora
    function actualizarHidden() {
        const fecha = inputFecha.value || hoy;
        const hora = inputHora.value;

        if (hora) {
            inputHidden.value = fecha + ' ' + hora + ':00';
        } else {
            inputHidden.value = '';
        }
    }

    // activa el botón de envío si el hidden tiene valor
    function actualizarSubmit() {
        if (!btnSubmit) return;

        if (inputHidden.value !== '') {
            btnSubmit.removeAttribute('disabled');
        } else {
            btnSubmit.setAttribute('disabled', 'disabled');
        }
    }

    // muestra el campo de hora y opcionalmente el de fecha
    function mostrarCampos(mostrarFecha) {
        campoHora.style.display = 'block';
        campoFecha.style.display = mostrarFecha ? 'block' : 'none';
    }

    // marca un botón como activo y desactiva el otro
    function marcarActivo(btnActivo, btnInactivo) {
        btnActivo.classList.add('activo');
        btnInactivo.classList.remove('activo');
    }

    // ── Eventos ──

    btnHoy.addEventListener('click', function () {
        marcarActivo(btnHoy, btnProgramar);
        inputFecha.value = hoy;
        mostrarCampos(false);
        actualizarHidden();
        actualizarSubmit();
    });

    btnProgramar.addEventListener('click', function () {
        marcarActivo(btnProgramar, btnHoy);
        mostrarCampos(true);
        actualizarHidden();
        actualizarSubmit();
    });

    inputFecha.addEventListener('change', function () {
        actualizarHidden();
        actualizarSubmit();
    });

    inputHora.addEventListener('change', function () {
        actualizarHidden();
        actualizarSubmit();
    });

});