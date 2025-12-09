<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require_once 'config.php';
require_once 'funciones/usuario.func.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode(["status" => "error", "message" => "No se recibió JSON"]);
        exit;
    }

    $nombre = $input['nombre_completo'] ?? "";
    $email = $input['email'] ?? "";
    $password = $input['password'] ?? "";
    $confirmar = $input['confirmar_password'] ?? "";

    if (!$nombre || !$email || !$password || !$confirmar) {
        echo json_encode(["status" => "error", "message" => "Faltan datos"]);
        exit;
    }

    if ($password !== $confirmar) {
        echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden"]);
        exit;
    }

    $resultado = registrarUsuario($nombre, $email, $password);

    echo json_encode($resultado);
    exit;
}

echo json_encode(["status" => "error", "message" => "Método no permitido"]);
