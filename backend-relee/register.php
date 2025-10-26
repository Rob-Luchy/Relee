<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

require_once 'config.php';

// Si el navegador hace una solicitud OPTIONS (preflight CORS), responder sin error
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode(["status" => "error", "message" => "No se recibieron datos"]);
        exit;
    }

    if (empty($input['nombre_completo']) || empty($input['email']) || empty($input['password'])) {
        echo json_encode(["status" => "error", "message" => "Faltan datos"]);
        exit;
    }

    $nombre = $input['nombre_completo'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_completo, email, password_hash) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Usuario registrado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar usuario"]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
