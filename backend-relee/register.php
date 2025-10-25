<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    file_put_contents("debug.log", print_r($input, true)); // 👈 para ver si llegan datos

    if (!isset($input['nombre_completo']) || !isset($input['email']) || !isset($input['password'])) {
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
<!-- Prueba de commit -->
