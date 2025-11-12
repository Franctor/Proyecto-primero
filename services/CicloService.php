<?php
namespace services;

use repositories\RepoCiclo;
use helpers\Converter;
class CicloService
{
    public function getCiclos()
    {
        $repoCiclo = new RepoCiclo();
        $ciclos = $repoCiclo->findAll(true);
        if (!empty($ciclos)) {
            $converter = new Converter();
            $ciclos = $converter->convertirCiclosAJson($ciclos);
        }
        return $ciclos;
    }

    public function getCiclo($idCiclo)
    {
        $repoCiclo = new RepoCiclo();
        $ciclo = $repoCiclo->findById($idCiclo, true);
        if ($ciclo) {
            $converter = new Converter();
            $ciclo = $converter->convertirCicloAJson($ciclo);
        }
        return $ciclo;
    }
}