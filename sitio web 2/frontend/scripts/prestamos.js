document.addEventListener('DOMContentLoaded', () => {
    const gridLibros = document.getElementById('grid-libros');
    let seleccionados = JSON.parse(sessionStorage.getItem('carrito_prestamos')) || [];

    // Cargar catálogo
    if (gridLibros) {
        fetch('../../backend/apis/libros.php')
            .then(res => res.json())
            .then(data => {
                gridLibros.innerHTML = '';
                data.forEach(libro => {
                    const card = document.createElement('div');
                    card.className = 'book-card';
                    card.innerText = libro.titulo;
                    card.dataset.id = libro.id;
                    
                    // Evento de Doble Clic para seleccionar libro
                    card.addEventListener('dblclick', () => {
                        if (!seleccionados.some(item => item.id === libro.id)) {
                            seleccionados.push(libro);
                            sessionStorage.setItem('carrito_prestamos', JSON.stringify(seleccionados));
                            alert(`Libro "${libro.titulo}" agregado al préstamo.`);
                        } else {
                            alert('Este libro ya fue seleccionado.');
                        }
                    });
                    
                    gridLibros.appendChild(card);
                });
            });
    }

    // Renderizar lista en formulario de préstamo
    const listaUl = document.getElementById('lista-seleccionados');
    if (listaUl) {
        listaUl.innerHTML = '';
        seleccionados.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.titulo} - ISBN: ${item.isbn}`;
            listaUl.appendChild(li);
        });
    }

    // Procesar Préstamo
    document.getElementById('form-prestamo')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (seleccionados.length === 0) {
            alert('Debe seleccionar al menos un libro del catálogo (doble clic).');
            return;
        }

        const nombre = document.getElementById('cliente_nombre').value;
        const edad = document.getElementById('cliente_edad').value;
        const sexo = document.getElementById('cliente_sexo').value;
        const direccion = document.getElementById('cliente_direccion').value;
        const libros = seleccionados.map(l => l.id);

        const res = await fetch('../../backend/apis/prestamos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, edad, sexo, direccion, libros })
        });

        const data = await res.json();
        if (data.status === 'success') {
            alert(`Préstamo registrado exitosamente.\nID Único del Cliente: ${data.identificador}`);
            sessionStorage.removeItem('carrito_prestamos');
            window.location.href = 'catalogo.html';
        }
    });
});
