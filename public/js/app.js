// Añade o quita la clase 'modo-noche' del body y guarda la preferencia en una cookie

function toggleModoNoche() {
    document.body.classList.toggle('modo-noche');
    let activado = document.body.classList.contains('modo-noche');
    document.cookie = 'modoNoche=' + activado + ';path=/;max-age=31536000';
    actualizarIconoModo(activado);
}

function actualizarIconoModo(modoNocheActivo) {
    const btn = document.getElementById('btn-modo');
    btn.textContent = modoNocheActivo ? '☀️' : '🌙';
    btn.title = modoNocheActivo ? 'Modo día' : 'Modo noche';
}

document.addEventListener('DOMContentLoaded', () => {
    let activado = document.cookie.includes('modoNoche=true');
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