<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'funciones/libro.func.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $resultado = registrarLibro(
        $data['id_usuario'] ?? "",
        $data['titulo'] ?? "",
        $data['autor'] ?? "",
        $data['descripcion'] ?? "",
        $data['id_categoria'] ?? "",
        $data['imagen_portada'] ?? ""
    );

    echo json_encode($resultado);
    exit;
}

echo json_encode(["status" => "error", "message" => "Método no permitido"]);
