<?php
header("Content-Type: application/json");
require_once "../connection/db.php";
require_once "../services/SessionService.php";

SessionService::checkAuth();
$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Generar identificador único para cliente
    $identificador = "CLI-" . uniqid();
    
    $stmt = $db->prepare("INSERT INTO clientes (identificador_unico, nombre_completo, edad, sexo, direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$identificador, $data['nombre'], $data['edad'], $data['sexo'], $data['direccion']]);
    $cliente_id = $db->lastInsertId();

    $stmtP = $db->prepare("INSERT INTO prestamos (cliente_id, usuario_id, fecha_devolucion_esperada) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))");
    $stmtP->execute([$cliente_id, $_SESSION['usuario_id']]);
    $prestamo_id = $db->lastInsertId();

    $stmtD = $db->prepare("INSERT INTO detalle_prestamo (prestamo_id, libro_id) VALUES (?, ?)");
    foreach ($data['libros'] as $libro_id) {
        $stmtD->execute([$prestamo_id, $libro_id]);
    }

    echo json_encode(["status" => "success", "identificador" => $identificador]);
}
?>
