// gestión del modal de sugerencias

document.addEventListener('DOMContentLoaded', function () {

    const modal       = document.getElementById('modalSugerencia');
    const formAñadir  = document.getElementById('formAñadir');
    const btnCerrar   = document.getElementById('modalCerrar');
    const btnCancelar = document.getElementById('modalCancelar');

    if (!modal) return;

    // ── Funciones ──

    function abrirModal(btn) {
        document.getElementById('modalTitulo').textContent      = btn.dataset.titulo;
        document.getElementById('modalDescripcion').textContent = btn.dataset.descripcion || 'Sin descripción.';
        document.getElementById('modalFrecuencia').textContent  = '🎯 ' + btn.dataset.frecuencia + 'x semana';
        document.getElementById('modalDificultad').textContent  = '⭐'.repeat(parseInt(btn.dataset.dificultad));

        const spanDuracion = document.getElementById('modalDuracion');
        if (btn.dataset.duracion) {
            spanDuracion.textContent   = '⏱ ' + btn.dataset.duracion + ' min';
            spanDuracion.style.display = 'inline-block';
        } else {
            spanDuracion.style.display = 'none';
        }

        formAñadir.action  = '/sugerencias/' + btn.dataset.id + '/añadir';
        modal.style.display = 'flex';
    }

    function cerrarModal() {
        modal.style.display = 'none';
    }

    // ── Eventos ──

    document.querySelectorAll('.btn-abrir-modal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            abrirModal(btn);
        });
    });

    btnCerrar.addEventListener('click', cerrarModal);
    btnCancelar.addEventListener('click', cerrarModal);

    // cerrar al pulsar fuera del modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            cerrarModal();
        }
    });

});