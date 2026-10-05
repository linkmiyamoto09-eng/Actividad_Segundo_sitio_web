<?php
session_start();

class SessionService {
    public static function checkAuth() {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Acceso no autorizado"]);
            exit;
        }
    }

    public static function checkAdmin() {
        self::checkAuth();
        if ($_SESSION['rol_id'] != 1) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "Permisos insuficientes"]);
            exit;
        }
    }
}
?>
