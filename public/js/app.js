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
    actualizarIconoModo(activado);
});