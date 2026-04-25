// gestión de intereses predefinidos y personalizados
console.log('intereses.js cargado');

document.addEventListener('DOMContentLoaded', function () {

    const inputNuevo      = document.getElementById('interes-nuevo');
    const btnAñadir       = document.getElementById('btn-añadir-interes');
    const contenedor      = document.getElementById('intereses-personalizados');

    if (!btnAñadir) return;

    // ── Funciones ──

    // añade un interés personalizado a la lista
    function añadirInteres() {
        const valor = inputNuevo.value.trim().toLowerCase();

        if (!valor) return;

        // evitamos duplicados
        const yaExiste = document.querySelector(`input[name="interests[]"][value="${valor}"]`);
        if (yaExiste) {
            inputNuevo.value = '';
            return;
        }

        // creamos el elemento visual
        const label = document.createElement('label');
        label.classList.add('interes-opcion', 'activo');

        const input = document.createElement('input');
        input.type    = 'checkbox';
        input.name    = 'interests[]';
        input.value   = valor;
        input.checked = true;

        const texto = document.createTextNode(valor.charAt(0).toUpperCase() + valor.slice(1));

        label.appendChild(input);
        label.appendChild(texto);
        contenedor.appendChild(label);

        // marcamos visualmente los predefinidos al hacer click
        label.addEventListener('click', function () {
            label.classList.toggle('activo', input.checked);
        });

        inputNuevo.value = '';
    }

    // ── Eventos ──

    btnAñadir.addEventListener('click', añadirInteres);

    // permite añadir con Enter
    inputNuevo.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            añadirInteres();
        }
    });

    // marcamos visualmente los intereses predefinidos al hacer click
    document.querySelectorAll('.interes-opcion input[type="checkbox"]').forEach(function (input) {
        input.addEventListener('change', function () {
            input.closest('.interes-opcion').classList.toggle('activo', input.checked);
        });
    });

});