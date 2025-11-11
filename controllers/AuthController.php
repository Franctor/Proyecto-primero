<?php
namespace controllers;
use League\Plates\Engine;
class AuthController
{
    private $templates;
    public function __construct(Engine $templates) {
        $this->templates = $templates;
    }
    public function login() {
        $this->templates->render('auth/login');
    }

    public function register() {
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            if ($tipo === 'alumno' || $tipo === 'empresa') {
                echo $this->templates->render('auth/register', ['tipo' => $tipo]);
            } else {
                header('Location: /index.php');
                exit;
            }
        } else {
            header('Location: /index.php');
            exit;
        }
    }
}