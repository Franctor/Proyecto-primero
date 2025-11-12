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
        if ($password != null) {
            $this->password = $password;
        } else {
            $this->generatePass();
        }

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

    public function setLocalidadId($localidad_id)
    {
        $this->localidad_id = $localidad_id;
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

    public function getPassword()
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
    public function generatePass()
    {
        // Listas de caracteres por tipo
        $minusculas = 'abcdefghijklmnopqrstuvwxyz';
        $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        $especiales = '!@#$%^&*()-_+=';

        // Aseguramos que haya al menos un carácter de cada tipo
        $password = '';
        $password .= $minusculas[rand(0, strlen($minusculas) - 1)];
        $password .= $mayusculas[rand(0, strlen($mayusculas) - 1)];
        $password .= $numeros[rand(0, strlen($numeros) - 1)];
        $password .= $especiales[rand(0, strlen($especiales) - 1)];

        // Rellenamos el resto de la contraseña hasta 10 caracteres
        $todos = $minusculas . $mayusculas . $numeros . $especiales;
        for ($i = 4; $i < 10; $i++) {
            $password .= $todos[rand(0, strlen($todos) - 1)];
        }

        // Mezclar los caracteres para no dejar los primeros fijos
        $password = str_shuffle($password);

        // Guardar la contraseña
        $this->setPassword($password);
    }

}
?>