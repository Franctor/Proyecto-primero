<?php
namespace services;
use repositories\RepoUsuario;
class UsuarioService
{
    private $userRepo;
    public function __construct()
    {
        $this->userRepo = new RepoUsuario();
    }
    public function getUserByNombreUsuario($nombre_usuario)
    {
        return $this->userRepo->findByNombreUsuario($nombre_usuario);
    }
}