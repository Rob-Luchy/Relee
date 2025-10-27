<?php
// Datos de conexión
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuario de XAMPP
define('DB_PASSWORD', '');     // Contraseña (vacía por defecto)
define('DB_NAME', 'relee_db'); // Nombre exacto de tu base

// Intentar conexión
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexión
if ($link === false) {
    die("❌ ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}

// Codificación UTF-8
mysqli_set_charset($link, "utf8mb4");
// Test de cambio para probar git status

?>
