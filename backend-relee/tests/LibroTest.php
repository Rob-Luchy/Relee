<?php

use PHPUnit\Framework\TestCase;

// Cargar la función correcta
require_once __DIR__ . '/../funciones/libro.func.php';

class LibroTest extends TestCase
{
    public function testRegistroLibroValido()
    {
        // registrarLibro($id_usuario, $titulo, $autor, $descripcion, $id_categoria, $imagen_portada)

        $resultado = registrarLibro(
            1,                  // id_usuario
            "El Principito",    // titulo
            "Saint-Exupéry",    // autor
            "Obra clásica",     // descripcion
            1,                  // id_categoria
            "imagen.jpg"        // imagen_portada
        );

        $this->assertEquals("success", $resultado["status"]);
    }

    public function testErrorDatosIncompletos()
    {
        $resultado = registrarLibro(
            "", "", "", "", "", ""
        );

        $this->assertEquals("error", $resultado["status"]);
    }
}
