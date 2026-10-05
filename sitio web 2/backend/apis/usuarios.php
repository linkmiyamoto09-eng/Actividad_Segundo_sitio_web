<?php
header("Content-Type: application/json");
require_once "../connection/db.php";
require_once "../services/SessionService.php";

SessionService::checkAdmin();
$db = (new Database())->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query("SELECT id, nombre_completo, correo FROM usuarios WHERE rol_id = 2 AND activo = 1");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $passHash = password_hash($data['password'], PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol_id) VALUES (?, ?, ?, 2)");
    $stmt->execute([$data['nombre'], $data['correo'], $passHash]);
    echo json_encode(["status" => "success"]);
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare("UPDATE usuarios SET activo = 0 WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["status" => "success"]);
}
?>
