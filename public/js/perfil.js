// gestión de la página de perfil

document.addEventListener('DOMContentLoaded', function () {

    const opcionesAvatar = document.querySelectorAll('.avatar-opcion');

    // si no hay selector de avatar en la página, no hacemos nada
    if (!opcionesAvatar.length) return;

    // ── Funciones ──

    // actualiza la clase activa del avatar seleccionado
    function actualizarAvatarActivo() {
        opcionesAvatar.forEach(function (opcion) {
            const input = opcion.querySelector('input[type="radio"]');
            opcion.classList.toggle('activo', input.checked);
        });
    }

    // ── Eventos ──

    opcionesAvatar.forEach(function (opcion) {
        opcion.addEventListener('change', actualizarAvatarActivo);
    });

    // inicializamos el estado al cargar
    actualizarAvatarActivo();

});
