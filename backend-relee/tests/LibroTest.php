<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../funciones/libro.func.php';

class LibroTest extends TestCase
{
    protected function setUp(): void
    {
        global $link;

        // Crear usuario segun tu estructura REAL
        mysqli_query($link, "
            INSERT IGNORE INTO usuarios (id, nombre_completo, email, password_hash)
            VALUES (1, 'Usuario Test', 'test@test.com', '123456')
        ");

        // Crear categoria segun tu estructura real
        mysqli_query($link, "
            INSERT IGNORE INTO categorias (id, nombre)
            VALUES (1, 'General')
        ");
    }

    public function testRegistroLibroValido()
    {
        $resultado = registrarLibro(
            1,
            "El Principito",
            "Saint-Exupéry",
            "Obra clásica",
            1,
            "imagen.jpg"
        );

        if ($resultado["status"] === "error") {
            var_dump($resultado);
        }

        $this->assertEquals("success", $resultado["status"]);
    }

    public function testErrorDatosIncompletos()
    {
        $resultado = registrarLibro("", "", "", "", "", "");

        $this->assertEquals("error", $resultado["status"]);
    }
}
