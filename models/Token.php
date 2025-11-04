<?php
namespace models;
class Token
{
    private $id;
    private $valor;
    private $usuario;

    public function __construct($valor = null, $usuario = null)
    {
        $this->id = null;
        // Si no se pasa un valor, se genera automáticamente
        $this->valor = $valor ?? $this->generarValor();
        $this->usuario = $usuario;
    }

    // --- Getters ---
    public function getId()
    {
        return $this->id;
    }

    public function getValor()
    {
        return $this->valor;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }

    // --- Setters ---
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    // --- Generador de token aleatorio ---
    private function generarValor()
    {
        return bin2hex(random_bytes(16)); // 32 caracteres hexadecimales (128 bits)
    }
}
?>