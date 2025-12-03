<?php
require_once __DIR__ . '/../config.php';

function registrarLibro($id_usuario, $titulo, $autor, $descripcion, $id_categoria, $imagen_portada)
{
    global $link;

    if (empty($id_usuario) || empty($titulo) || empty($imagen_portada)) {
        return ["status" => "error", "message" => "Faltan datos"];
    }

    $estado = "Usado";
    $disponibilidad = "Disponible";

    $sql = "INSERT INTO libros (id_usuario, id_categoria, titulo, autor, descripcion, imagen_portada, estado, disponibilidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iissssss",
        $id_usuario,
        $id_categoria,
        $titulo,
        $autor,
        $descripcion,
        $imagen_portada,
        $estado,
        $disponibilidad
    );

    if (mysqli_stmt_execute($stmt)) {
        return ["status" => "success", "message" => "Libro registrado"];
    }

   return ["status" => "error", "message" => mysqli_error($link)];

}
