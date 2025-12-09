<?php
// -------------------------------
// 🔥 CORS para Angular 4200
// -------------------------------
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Responder las solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// -------------------------------
// 🔥 Aquí empieza tu lógica real
// -------------------------------
require_once 'config.php';
require_once 'funciones/usuario.func.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $input = json_decode(file_get_contents("php://input"), true);
    
    $email = $input['email'] ?? "";
    $password = $input['password'] ?? "";

    $resultado = loginUsuario($email, $password);

    echo json_encode($resultado);
    exit;
}

// Si no es POST
echo json_encode(["status" => "error", "message" => "Método no permitido"]);
