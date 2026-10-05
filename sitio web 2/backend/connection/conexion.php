<?php
function conectarBD($rol = 'empleado') {
    $host = 'localhost';
    $db   = 'biblioteca_db';
    
    // Asignación de credenciales según rol sin usar root
    if ($rol === 'admin') {
        $user = 'admin_user';
        $pass = 'AdminPass2026!';
    } else {
        $user = 'empleado_user';
        $pass = 'EmpleadoPass2026!';
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error en conexión a la base de datos: " . $e->getMessage());
    }
}
?>