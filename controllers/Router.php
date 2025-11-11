<?php
namespace controllers;

use Controllers\HomeController;
use League\Plates\Engine;
class Router
{
    public function route()
    {
        $templates = new Engine(__DIR__ . '/../views');

        if (isset($_GET['menu'])) {
            $opcion = $_GET['menu'];
            switch ($opcion) {
                case 'adminPanel':
                    $controller = new AdminController($templates);
                    ;
                    $controller->adminPanel();
                    break;
                case 'login':
                    // Lógica para el login
                    $controller = new AuthController($templates);
                    $controller->login();
                    break;
                case 'register':
                    // Lógica para el registro
                    $controller = new AuthController($templates);
                    $controller->register();
                    break;
                default:
                    header('Location: /');
                    exit;
            }
        } else {
            $controller = new HomeController($templates);
            $controller->landingPage();
        }
    }
}