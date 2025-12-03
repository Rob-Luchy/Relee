<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

require_once 'config.php';

$sql = "SELECT id, nombre FROM categorias_libros";
$result = mysqli_query($link, $sql);

$categorias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categorias[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $categorias
]);

mysqli_close($link);
?>
