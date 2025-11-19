<?php
namespace controllers;

use controllers\SolicitudController;
use Controllers\HomeController;
use League\Plates\Engine;
use controllers\AuthController;
use helpers\Session;
class Router
{
    public function route()
    {
        $templates = new Engine(__DIR__ . '/../views');
        $templates->addData([
            'usuario' => Session::get('user'),
            'perfil' => Session::get('perfil'),
            'tipo' => Session::get('tipo')
        ]);
        if (isset($_GET['menu'])) {
            $opcion = $_GET['menu'];
            switch ($opcion) {
                case 'adminPanel':
                    $controller = new AdminController($templates);
                    $controller->adminPanel();
                    break;
                case 'login':
                    $controller = new AuthController($templates);
                    $controller->login();
                    break;
                case 'register':
                    $controller = new AuthController($templates);
                    $controller->register();
                    break;
                case 'logout':
                    $controller = new AuthController($templates);
                    $controller->logout();
                    break;
                case 'ofertas':
                    $controller = new OfertaController($templates);
                    $controller->ofertas();
                    break;
                case 'solicitudes':
                    $controller = new SolicitudController($templates);
                    $controller->solicitudes();
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