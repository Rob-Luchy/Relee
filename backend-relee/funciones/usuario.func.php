<?php
require_once __DIR__ . '/../config.php';

function registrarUsuario($nombre, $email, $password)
{
    global $link;

    if (empty($nombre) || empty($email) || empty($password)) {
        return ["status" => "error", "message" => "Faltan datos"];
    }

    // Verificar duplicado
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_assoc($result)) {
        return ["status" => "error", "message" => "Email duplicado"];
    }

    // Registrar usuario
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_completo, email, password_hash)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $password_hash);

    if (mysqli_stmt_execute($stmt)) {
        return ["status" => "success", "message" => "Usuario registrado"];
    }

    return ["status" => "error", "message" => "Error al registrar usuario"];
}

function loginUsuario($email, $password)
{
    global $link;

    if (empty($email) || empty($password)) {
        return ["status" => "error", "message" => "Faltan datos"];
    }

    $sql = "SELECT id, nombre_completo, email, password_hash
            FROM usuarios WHERE email = ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $user['password_hash'])) {
            return [
                "status" => "success",
                "message" => "Login correcto",
                "user" => [
                    "id" => $user["id"],
                    "nombre_completo" => $user["nombre_completo"],
                    "email" => $user["email"]
                ]
            ];
        }

        return ["status" => "error", "message" => "Contraseña incorrecta"];
    }

    return ["status" => "error", "message" => "Usuario no encontrado"];
}
