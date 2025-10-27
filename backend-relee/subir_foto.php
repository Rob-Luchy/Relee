<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json');
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id_libro']) || !isset($input['ruta_imagen'])) {
        echo json_encode(["status" => "error", "message" => "Faltan datos"]);
        exit;
    }

    $id_libro = $input['id_libro'];
    $ruta_imagen = $input['ruta_imagen'];

    $sql = "INSERT INTO fotos_libros (id_libro, ruta_imagen) VALUES (?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id_libro, $ruta_imagen);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Foto guardada correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al guardar foto"]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}

elseif ($method === 'GET') {
    if (!isset($_GET['id_libro'])) {
        echo json_encode(["status" => "error", "message" => "Falta el parámetro id_libro"]);
        exit;
    }

    $id_libro = intval($_GET['id_libro']);
    $sql = "SELECT * FROM fotos_libros WHERE id_libro = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_libro);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $fotos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fotos[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $fotos]);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
}

else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
