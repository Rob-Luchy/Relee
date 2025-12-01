<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'funciones/usuario.func.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    $nombre = $input['nombre_completo'] ?? "";
    $email = $input['email'] ?? "";
    $password = $input['password'] ?? "";

    $resultado = registrarUsuario($nombre, $email, $password);

    echo json_encode($resultado);
    exit;
}

echo json_encode(["status" => "error", "message" => "Método no permitido"]);
