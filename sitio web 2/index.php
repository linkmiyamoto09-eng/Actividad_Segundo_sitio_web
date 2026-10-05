<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Internacional - Inicio</title>
    <link rel="stylesheet" href="frontend/styles/main.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="frontend/img/logo.png" alt="Logo Biblioteca" id="main-logo">
        </div>
        <nav class="nav-buttons">
            <a href="frontend/pages/categorias.html" class="btn">CATEGORÍAS</a>
            <a href="frontend/pages/login.html" class="btn">INICIO DE SESIÓN</a>
        </nav>
    </header>

    <main>
        <section id="hero" style="text-align: center; margin-bottom: 2rem;">
            <div class="logo-display">
                <img src="frontend/img/logo.png" alt="Logo Grande" style="max-width: 250px;">
            </div>
        </section>

        <article id="about-us" style="border: 1px solid var(--graphite); padding: 2rem; background: var(--dry-sage);">
            <h2>ACERCA DE NOSOTROS</h2>
            <p>INFORMACIÓN DE LA INSTITUCIÓN</p>
            <p>Bienvenido a la Biblioteca Internacional. Un espacio dedicado a la difusión de la literatura, el conocimiento y el aprendizaje continuo para nuestra comunidad.</p>
        </article>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Biblioteca Internacional. Todos los derechos reservados. | Información de la Institución</p>
    </footer>
    <script src="frontend/scripts/app.js"></script>
</body>
</html>
