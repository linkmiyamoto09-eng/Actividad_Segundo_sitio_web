<?php
session_start();
header('Content-Type: application/json');

function conectarBD($rol = 'empleado') {
    $host = 'localhost';
    $db   = 'biblioteca_db';
    $user = ($rol === 'admin') ? 'admin_user' : 'empleado_user';
    $pass = ($rol === 'admin') ? 'AdminPass2026!' : 'EmpleadoPass2026!';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la conexión']);
        exit;
    }
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = conectarBD('empleado');
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$data['correo'] ?? '']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'] ?? '', $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol_id'] = $user['rol_id'];
            $_SESSION['nombre'] = $user['nombre_completo'];
            echo json_encode(['success' => true, 'rol_id' => $user['rol_id']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    case 'obtener_empleados':
        $pdo = conectarBD('admin');
        $stmt = $pdo->query("SELECT id, nombre_completo, correo FROM usuarios WHERE rol_id = 2");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'crear_empleado':
        $pdo = conectarBD('admin');
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol_id) VALUES (?, ?, ?, 2)");
        $pass = password_hash($data['password'], PASSWORD_DEFAULT);
        $res = $stmt->execute([$data['nombre'], $data['correo'], $pass]);
        echo json_encode(['success' => $res]);
        break;

    case 'eliminar_empleado':
        $pdo = conectarBD('admin');
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ? AND rol_id = 2");
        echo json_encode(['success' => $stmt->execute([$id])]);
        break;

    case 'obtener_libros':
        $pdo = conectarBD('empleado');
        $stmt = $pdo->query("SELECT * FROM libros");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'obtener_categorias':
        $pdo = conectarBD('empleado');
        $stmt = $pdo->query("SELECT * FROM categorias");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'crear_libro':
        $pdo = conectarBD('empleado');
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO libros (titulo, autor, isbn, categoria_id) VALUES (?, ?, ?, ?)");
        $res = $stmt->execute([$data['titulo'], $data['autor'], $data['isbn'], $data['categoria_id']]);
        echo json_encode(['success' => $res]);
        break;

    case 'eliminar_libro':
        $pdo = conectarBD('empleado');
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM libros WHERE id = ?");
        echo json_encode(['success' => $stmt->execute([$id])]);
        break;

    case 'obtener_deudores':
        $pdo = conectarBD('empleado');
        $stmt = $pdo->query("
            SELECT c.nombre_completo, l.titulo 
            FROM prestamos p 
            JOIN clientes c ON p.cliente_id = c.id 
            JOIN detalle_prestamos dp ON p.id = dp.prestamo_id 
            JOIN libros l ON dp.libro_id = l.id 
            WHERE p.estatus = 'activo'
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'registrar_prestamo':
        $pdo = conectarBD('empleado');
        $data = json_decode(file_get_contents('php://input'), true);
        $identificador_unico = 'CLI-' . strtoupper(uniqid());

        $stmt = $pdo->prepare("INSERT INTO clientes (identificador_unico, nombre_completo, edad, sexo, correo, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$identificador_unico, $data['nombre_completo'], $data['edad'], $data['sexo'], $data['correo'], $data['direccion']]);
        $cliente_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO prestamos (cliente_id, empleado_id) VALUES (?, ?)");
        $stmt->execute([$cliente_id, $_SESSION['user_id'] ?? 1]);
        $prestamo_id = $pdo->lastInsertId();

        $stmtDetalle = $pdo->prepare("INSERT INTO detalle_prestamos (prestamo_id, libro_id) VALUES (?, ?)");
        foreach ($data['libros_ids'] as $libro_id) {
            $stmtDetalle->execute([$prestamo_id, $libro_id]);
        }

        echo json_encode(['success' => true, 'identificador' => $identificador_unico]);
        break;

    default:
        echo json_encode(['error' => 'Acción no contemplada']);
        break;
}
?>