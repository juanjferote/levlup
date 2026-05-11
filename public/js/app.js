// Añade o quita la clase 'modo-noche' del body y guarda la preferencia en localStorage

function toggleModoNoche() {
    document.body.classList.toggle('modo-noche');
    let activado = document.body.classList.contains('modo-noche');
    localStorage.setItem('modoNoche', activado);
    // Actualizar icono según el modo actual
    actualizarIconoModo(activado);
}

function actualizarIconoModo(modoNocheActivo) {
    const btn = document.getElementById('btn-modo');
    btn.textContent = modoNocheActivo ? '☀️' : '🌙';
    btn.title = modoNocheActivo ? 'Modo día' : 'Modo noche';
}

document.addEventListener('DOMContentLoaded', () => {
    let activado = localStorage.getItem('modoNoche') === 'true';
    if (activado) {
        document.body.classList.add('modo-noche');
    }
    document.querySelectorAll('.pestana').forEach(function(pestana) {
        pestana.addEventListener('click', function() {
            // buscamos el contenedor padre más cercano que agrupe las pestañas
            const contenedor = pestana.closest('.bloque, .contenido-principal');

            // solo afectamos a las pestañas y contenidos dentro de ese contenedor
            contenedor.querySelectorAll('.pestana').forEach(p => p.classList.remove('activa'));
            contenedor.querySelectorAll('.pestana-contenido').forEach(c => c.classList.remove('activo'));

            pestana.classList.add('activa');
            document.getElementById('pestana-' + pestana.dataset.pestana).classList.add('activo');
        });
    });
    actualizarIconoModo(activado);
});
