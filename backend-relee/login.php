<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['email']) || !isset($input['password'])) {
        echo json_encode(["status" => "error", "message" => "Faltan datos"]);
        exit;
    }

    $email = $input['email'];
    $password = $input['password'];

    $sql = "SELECT id, nombre_completo, email, password_hash FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password_hash'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Inicio de sesión exitoso",
                "user" => [
                    "id" => $user['id'],
                    "nombre_completo" => $user['nombre_completo'],
                    "email" => $user['email']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
