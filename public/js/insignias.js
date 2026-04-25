// tooltip de insignias al pasar el ratón

document.addEventListener('DOMContentLoaded', function () {

    // creamos el tooltip una sola vez y lo añadimos al body
    const tooltip = document.createElement('div');
    tooltip.classList.add('insignia-tooltip');
    tooltip.style.display = 'none';
    document.body.appendChild(tooltip);

    const tarjetas = document.querySelectorAll('.insignia-card');

    if (!tarjetas.length) return;

    // ── Funciones ──

    function mostrarTooltip(e, card) {
        const nombre      = card.dataset.nombre;
        const descripcion = card.dataset.descripcion;
        const rareza      = card.dataset.rareza;

        tooltip.innerHTML = `
            <span class="tooltip-nombre">${nombre}</span>
            <span class="tooltip-descripcion">${descripcion}</span>
            <span class="tooltip-rareza">${rareza}</span>
        `;

        tooltip.style.display = 'flex';
        moverTooltip(e);
    }

    function moverTooltip(e) {
        const margen = 12;
        let x = e.clientX + margen;
        let y = e.clientY + margen;

        // evitamos que el tooltip salga de la pantalla por la derecha
        if (x + tooltip.offsetWidth > window.innerWidth) {
            x = e.clientX - tooltip.offsetWidth - margen;
        }

        tooltip.style.left = x + 'px';
        tooltip.style.top  = y + window.scrollY + 'px';
    }

    function ocultarTooltip() {
        tooltip.style.display = 'none';
    }

    // ── Eventos ──

    tarjetas.forEach(function (card) {
        card.addEventListener('mouseenter', function (e) {
            mostrarTooltip(e, card);
        });

        card.addEventListener('mousemove', moverTooltip);

        card.addEventListener('mouseleave', ocultarTooltip);
    });

});