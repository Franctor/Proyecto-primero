<?php
namespace controllers;
use helpers\Session;
use League\Plates\Engine;
class SolicitudController
{
    private $templates;
    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }

    public function solicitudes()
    {
        if (!(Session::isLogged())) {
            header('Location: index.php?menu=login');
            exit;
        }
        if (Session::get('tipo') === 'empresa') {
            $this->manejoEmpresa();
        } else if (Session::get('tipo') === 'alumno') {
            $this->manejoAlumno();
        }
    }

    private function manejoEmpresa() {
        echo $this->templates->render('solicitudes/solicitudesEmpresa');
    }

    private function manejoAlumno() {
        echo $this->templates->render('solicitudes/solicitudesAlumno');
    }
}