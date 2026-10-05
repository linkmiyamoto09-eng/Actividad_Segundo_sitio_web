<?php
header("Content-Type: application/json");
require_once "../connection/db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $data['correo'] ?? '';
    $password = $data['password'] ?? '';

    $db = (new Database())->getConnection();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = :correo AND activo = 1");
    $stmt->execute(['correo' => $correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre_completo'];
        $_SESSION['rol_id'] = $user['rol_id'];

        echo json_encode([
            "status" => "success",
            "rol_id" => $user['rol_id'],
            "redirect" => $user['rol_id'] == 1 ? "admin_empleados.html" : "catalogo.html"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
}
?>
