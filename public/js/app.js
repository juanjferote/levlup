// --- Modo noche ---
// Añade o quita la clase 'modo-noche' del body y guarda la preferencia en localStorage

function toggleModoNoche() {
    document.body.classList.toggle('modo-noche');
    // Guardar si está activado
    let activado = document.body.classList.contains('modo-noche');
    localStorage.setItem('modoNoche', activado);
}

// Comprobar si el modo noche está activado cuando se carga la página
document.addEventListener('DOMContentLoaded', ()=> {
    if (localStorage.getItem('modoNoche') === 'true') {
        document.body.classList.add('modo-noche');
    }
});