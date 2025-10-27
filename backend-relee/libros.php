<?php
// ===========================
// 📘 API: Gestión de Libros
// ===========================

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

// Permitir preflight OPTIONS (Angular)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ===================================================
// 📤 MÉTODO POST → Registrar un nuevo libro
// ===================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si viene con JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Si no hay JSON, intentamos con form-data (por subida de imágenes)
    if (!$data && isset($_POST['titulo'])) {
        $data = $_POST;
    }

    // Validar campos obligatorios
    if (
        !isset($data['id_usuario']) ||
        !isset($data['titulo']) ||
        !isset($data['imagen_portada'])
    ) {
        echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios."]);
        exit;
    }

    $id_usuario = $data['id_usuario'];
    $titulo = $data['titulo'];
    $autor = $data['autor'] ?? 'Desconocido';
    $descripcion = $data['descripcion'] ?? '';
    $id_categoria = $data['id_categoria'] ?? null;
    $estado = $data['estado'] ?? 'Usado';
    $disponibilidad = $data['disponibilidad'] ?? 'Disponible';
    $imagen_portada = $data['imagen_portada']; // URL o ruta de la imagen

    $sql = "INSERT INTO libros (id_usuario, id_categoria, titulo, autor, descripcion, imagen_portada, estado, disponibilidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iissssss", $id_usuario, $id_categoria, $titulo, $autor, $descripcion, $imagen_portada, $estado, $disponibilidad);

  if (mysqli_stmt_execute($stmt)) {
    $id_libro = mysqli_insert_id($link);
    echo json_encode(["status" => "success", "message" => "Libro registrado correctamente", "id_libro" => $id_libro]);
}
 else {
        echo json_encode(["status" => "error", "message" => "Error al registrar el libro."]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
    exit;
}

// ===================================================
// 📥 MÉTODO GET → Listar libros (por usuario o todos)
// ===================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $libros = [];
    $sql = "SELECT l.*, c.nombre AS categoria_nombre 
            FROM libros l 
            LEFT JOIN categorias_libros c ON l.id_categoria = c.id";

    // Si se pasa ?usuario=ID, filtra por usuario
    if (isset($_GET['usuario'])) {
        $usuario = intval($_GET['usuario']);
        $sql .= " WHERE l.id_usuario = $usuario";
    }

    $result = mysqli_query($link, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $libros[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $libros]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al obtener los libros."]);
    }

    mysqli_close($link);
    exit;
}

// ===================================================
// 🚫 Si llega otro método (PUT, DELETE, etc.)
// ===================================================
echo json_encode(["status" => "error", "message" => "Método no permitido."]);
exit;

?>
