<?php
namespace services;
use repositories\RepoUsuario;
class UsuarioService
{
    public function getUserByNombreUsuario($nombre_usuario){
        $repoUsuario = new RepoUsuario();
        return $repoUsuario->findByNombreUsuario($nombre_usuario);
    }
}