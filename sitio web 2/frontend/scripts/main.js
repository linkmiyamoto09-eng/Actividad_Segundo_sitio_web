// Manejo de Estado del Carrito de Préstamos mediante LocalStorage

function obtenerListaPrestamo() {
    return JSON.parse(localStorage.getItem('prestamo_libros')) || [];
}

function actualizarContador() {
    const lista = obtenerListaPrestamo();
    const countElem = document.getElementById('count-prestamo');
    if (countElem) {
        countElem.textContent = lista.length;
    }
}

function agregarAlPrestamo(id, titulo) {
    let lista = obtenerListaPrestamo();
    if (!lista.some(item => item.id === id)) {
        lista.push({ id, titulo });
        localStorage.setItem('prestamo_libros', JSON.stringify(lista));
        alert(`Libro "${titulo}" agregado a la solicitud de préstamo.`);
        actualizarContador();
    } else {
        alert("El libro ya está en la lista de solicitudes.");
    }
}

function verLista() {
    const lista = obtenerListaPrestamo();
    if (lista.length === 0) {
        alert("No has agregado libros a la lista de préstamo.");
    } else {
        const nombres = lista.map(l => l.titulo).join('\n- ');
        alert("Libros en lista para préstamo:\n- " + nombres);
    }
}

function renderizarFormularioPrestamo() {
    const lista = obtenerListaPrestamo();
    const container = document.getElementById('lista-seleccionados');
    const inputHidden = document.getElementById('libros_ids');

    if (container && inputHidden) {
        container.innerHTML = '';
        const ids = lista.map(item => item.id);
        inputHidden.value = JSON.stringify(ids);

        if (lista.length === 0) {
            container.innerHTML = '<li>No hay libros seleccionados. Regrese al catálogo.</li>';
        } else {
            lista.forEach(libro => {
                const li = document.createElement('li');
                li.textContent = libro.titulo;
                container.appendChild(li);
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    actualizarContador();
});