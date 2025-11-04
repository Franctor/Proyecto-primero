<?php
namespace models;
class Usuario
{
    private $id;
    private $nombre_usuario;
    private $password;
    private $rol_id;
    private $localidad_id;
    private $tokens = [];

    public function __construct($nombre_usuario, $password, $rol_id, $localidad_id, $tokens = [])
    {
        $this->id = null;
        $this->nombre_usuario = $nombre_usuario;
        // Se guarda la contraseña encriptada al crear el usuario
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->rol_id = $rol_id;
        $this->localidad_id = $localidad_id;
        $this->tokens = $tokens;
    }

    // --- Getters ---
    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }

    public function getRolId()
    {
        return $this->rol_id;
    }

    public function getLocalidadId()
    {
        return $this->localidad_id;
    }

    // --- Setters ---
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombreUsuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function setPassword($password)
    {
        // Siempre se encripta al asignar una nueva contraseña
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;
    }

    // --- Método para verificar la contraseña ---
    public function verificarPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function getPasswordHash()
    {
        return $this->password;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function addToken(Token $token)
    {
        $this->tokens[] = $token;
    }
}
?>