document.addEventListener('DOMContentLoaded', () => {
    // Cargar empleados para Admin
    const containerEmp = document.getElementById('empleados-container');
    if (containerEmp) {
        cargarEmpleados();

        document.getElementById('form-empleado')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const nombre = document.getElementById('nombre_empleado').value;
            const correo = document.getElementById('correo_empleado').value;
            const password = document.getElementById('pass_empleado').value;

            await fetch('../../backend/apis/usuarios.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, correo, password })
            });
            cargarEmpleados();
        });
    }

    // Cargar libros para Registro/Eliminación
    const containerLibros = document.getElementById('lista-libros-eliminar');
    if (containerLibros) {
        cargarLibrosEliminar();

        document.getElementById('form-nuevo-libro')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const titulo = document.getElementById('libro_titulo').value;
            const categoria_id = document.getElementById('libro_categoria').value;
            const autor = document.getElementById('libro_autor').value;
            const isbn = document.getElementById('libro_isbn').value;

            await fetch('../../backend/apis/libros.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ titulo, categoria_id, autor, isbn })
            });
            cargarLibrosEliminar();
        });
    }
});

async function cargarEmpleados() {
    const res = await fetch('../../backend/apis/usuarios.php');
    const data = await res.json();
    const container = document.getElementById('empleados-container');
    container.innerHTML = data.map(emp => `
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; background:var(--dry-sage); padding:10px; border:1px solid #000;">
            <span>${emp.nombre_completo} (${emp.correo})</span>
            <button class="btn" onclick="eliminarEmpleado(${emp.id})">ELIMINAR</button>
        </div>
    `).join('');
}

async function eliminarEmpleado(id) {
    await fetch('../../backend/apis/usuarios.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    cargarEmpleados();
}

async function cargarLibrosEliminar() {
    const res = await fetch('../../backend/apis/libros.php');
    const data = await res.json();
    const container = document.getElementById('lista-libros-eliminar');
    container.innerHTML = data.map(libro => `
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; background:var(--dry-sage); padding:10px; border:1px solid #000;">
            <span>${libro.titulo} - ${libro.autor}</span>
            <button class="btn" onclick="eliminarLibro(${libro.id})">ELIMINAR</button>
        </div>
    `).join('');
}

async function eliminarLibro(id) {
    await fetch('../../backend/apis/libros.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    cargarLibrosEliminar();
}
