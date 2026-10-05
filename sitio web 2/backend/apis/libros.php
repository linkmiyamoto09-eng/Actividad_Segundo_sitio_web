<?php
header("Content-Type: application/json");
require_once "../connection/db.php";

$db = (new Database())->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query("SELECT l.id, l.titulo, l.autor, l.isbn, c.nombre as categoria FROM libros l JOIN categorias c ON l.categoria_id = c.id WHERE l.disponible = 1");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare("INSERT INTO libros (titulo, autor, isbn, categoria_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['titulo'], $data['autor'], $data['isbn'], $data['categoria_id']]);
    echo json_encode(["status" => "success"]);
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare("UPDATE libros SET disponible = 0 WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["status" => "success"]);
}
?>
