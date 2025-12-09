<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../funciones/usuario.func.php';

class UsuarioTest extends TestCase
{
    public function testRegistroUsuarioValido()
    {
        $emailUnico = "usuario_" . uniqid() . "@mail.com";

        $res = registrarUsuario("Testing", $emailUnico, "123456");

        $this->assertEquals("success", $res["status"]);
    }

    public function testEmailDuplicado()
    {
        registrarUsuario("Test2", "dup@mail.com", "123456");

        $res = registrarUsuario("Test2", "dup@mail.com", "123456");

        $this->assertEquals("error", $res["status"]);
    }
}
