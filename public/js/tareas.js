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


    // activa el botón solo si la fecha/hora es válida, y avisa si la hora ya pasó
    function actualizarSubmit() {
        if (!btnSubmit) return;

        const valido = fechaEsValida();
        btnSubmit.toggleAttribute('disabled', !valido);

        // aviso visual si el usuario elige hoy con hora pasada
        const avisoExistente = document.getElementById('aviso-hora-pasada');
        const horaEsPasada = inputHidden.value && !valido;

        if (horaEsPasada && !avisoExistente) {
            const aviso = document.createElement('span');
            aviso.id = 'aviso-hora-pasada';
            aviso.className = 'form-error';
            aviso.textContent = 'La hora indicada ya ha pasado. Elige una hora futura.';
            inputHora.insertAdjacentElement('afterend', aviso);
        } else if (!horaEsPasada && avisoExistente) {
            avisoExistente.remove();
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

    /**
     * Comprueba si el scheduled_at montado es válido:
     * - Debe tener valor.
     * - Si la fecha es hoy, la hora debe ser estrictamente futura (al menos 1 minuto de margen).
     */

    function fechaEsValida() {
        if (!inputHidden.value) return false;

        const fechaSeleccionada = inputFecha.value || hoy;

        // solo aplicamos restricción de hora si la fecha es hoy
        if (fechaSeleccionada === hoy) {
            const ahora = new Date();
            const [hh, mm] = inputHora.value.split(':').map(Number);
            const horaElegida = new Date();
            horaElegida.setHours(hh, mm, 0, 0);

            // 1 minuto de margen para evitar rechazo de Laravel por milisegundos
            return horaElegida.getTime() > ahora.getTime() + 60 * 1000;
        }

        return true;
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