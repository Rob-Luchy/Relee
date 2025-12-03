<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once 'config.php';

// Manejo del preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

/* =====================================================
   🟦 1. OBTENER LIBROS (GET)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['usuario'])) {
        $idUsuario = intval($_GET['usuario']);
        $sql = "SELECT * FROM libros WHERE id_usuario = $idUsuario ORDER BY id DESC";
    } else {
        $sql = "SELECT * FROM libros ORDER BY id DESC";
    }

    $result = mysqli_query($link, $sql);
    $libros = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $libros[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $libros
    ]);
    exit;
}

/* =====================================================
   🟩 2. INSERTAR LIBRO (POST)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!$data) {
        echo json_encode([
            "status" => "error",
            "message" => "Datos no recibidos",
            "raw" => $raw
        ]);
        exit;
    }

    $id_usuario = $data['id_usuario'] ?? null;
    $titulo = $data['titulo'] ?? null;
    $autor = $data['autor'] ?? '';
    $id_categoria = $data['id_categoria'] ?? null;
    $descripcion = $data['descripcion'] ?? '';
    $imagen_portada = $data['imagen_portada'] ?? null;
    $estado = $data['estado'] ?? 'Usado';
    $disponibilidad = $data['disponibilidad'] ?? 'Disponible';

    if (!$id_usuario || !$titulo || !$imagen_portada || !$id_categoria) {
        echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
        exit;
    }

    $sql = "INSERT INTO libros (id_usuario, titulo, autor, id_categoria, descripcion, imagen_portada, estado, disponibilidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ississss", 
        $id_usuario, 
        $titulo, 
        $autor, 
        $id_categoria, 
        $descripcion, 
        $imagen_portada, 
        $estado, 
        $disponibilidad
    );

    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        echo json_encode(["status" => "success", "message" => "Libro registrado"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($link)]);
    }

    exit;
}

/* =====================================================
   🟥 3. ELIMINAR LIBRO (DELETE)
   ===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    if (!isset($_GET['id'])) {
        echo json_encode(["status" => "error", "message" => "ID de libro faltante"]);
        exit;
    }

    $id = intval($_GET['id']);
    $sql = "DELETE FROM libros WHERE id = $id";

    if (mysqli_query($link, $sql)) {
        echo json_encode(["status" => "success", "message" => "Libro eliminado"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($link)]);
    }

    exit;
}

echo json_encode(["status" => "error", "message" => "Método no permitido"]);
exit;
