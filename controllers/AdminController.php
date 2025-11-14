<?php
namespace controllers;
use helpers\Session;
use League\Plates\Engine;
class AdminController
{
    private $templates;
    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }
    public function adminPanel()
    {
        if (Session::isLogged() && Session::get('rol') === 1) {
            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
                switch ($accion) {
                    case 'panelAlumnos':
                        echo $this->templates->render('admin/panelAdmin', ['seccion' => 'alumnos']);
                        break;
                    case 'panelEmpresas':
                        echo $this->templates->render('admin/panelAdmin', ['seccion' => 'empresas']);
                        break;
                    case 'panelSolicitudes':
                        echo $this->templates->render('admin/panelAdmin', ['seccion' => 'solicitudes']);
                        break;
                    case 'panelOfertas':
                        echo $this->templates->render('admin/panelAdmin', ['seccion' => 'ofertas']);
                        break;
                    default:
                        echo $this->templates->render('admin/panelAdmin');
                        break;
                }
            } else {
                echo $this->templates->render('admin/panelAdmin');
            }
        }else {
            header('Location: index.php');
            exit;
        }
    }

}