<?php
namespace services;

use repositories\RepoCiclo;
use helpers\Converter;
class CicloService
{
    private $repoCiclo;
    public function __construct()
    {
        $this->repoCiclo = new RepoCiclo();
    }
    public function getCiclos()
    {
        $ciclos = $this->repoCiclo->findAll(true);
        if (!empty($ciclos)) {
            $converter = new Converter();
            $ciclos = $converter->convertirCiclosAJson($ciclos);
        }
        return $ciclos;
    }

    public function getCiclo($idCiclo)
    {
        $ciclo = $this->repoCiclo->findById($idCiclo, true);
        if ($ciclo) {
            $converter = new Converter();
            $ciclo = $converter->convertirCicloAJson($ciclo);
        }
        return $ciclo;
    }

    public function getAllCiclosSinFamilia()
    {
        return $this->repoCiclo->findAll();
    }

    public function getCiclosAlumno($alumnoId) {
        return $this->repoCiclo->findByAlumnoId($alumnoId);
    }
}