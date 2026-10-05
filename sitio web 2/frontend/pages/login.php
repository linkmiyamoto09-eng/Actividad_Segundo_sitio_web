<?php
session_start();
require_once 'php/conexion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    $pdo = conectarBD('empleado');
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['nombre'] = $user['nombre_completo'];

        if ($user['rol_id'] == 1) {
            header('Location: admin_empleados.php');
        } else {
            header('Location: catalogo.php');
        }
        exit;
    } else {
        $error = 'Credenciales inválidas';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo Biblioteca" class="logo">
            <h1>BIBLIOTECA INTERNACIONAL</h1>
        </div>
    </header>

    <main>
        <section class="card" style="max-width: 500px; margin: 2rem auto;">
            <h2>INICIO DE SESIÓN</h2>
            <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>NOMBRE COMPLETO / CORREO:</label>
                    <input type="email" name="correo" required>
                </div>
                <div class="form-group">
                    <label>CONTRASEÑA:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">INICIO DE SESIÓN</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Biblioteca Internacional. Todos los derechos reservados.</p>
    </footer>
</body>
</html>