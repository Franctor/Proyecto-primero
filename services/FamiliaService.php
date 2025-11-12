<?php
namespace services;
use repositories\RepoFamilia;
use helpers\Converter;
class FamiliaService
{
    public function getFamilias()
    {
        $repoFamilia = new RepoFamilia();
        $familias = $repoFamilia->findAll(true);
        if (!empty($familias)) {
            $converter = new Converter();
            $familias = $converter->convertirFamiliasAJson($familias);
        }
        return $familias;
    }

    public function getFamilia($idFamilia)
    {
        $repoFamilia = new RepoFamilia();
        $familia = $repoFamilia->findById($idFamilia, true);
        if ($familia) {
            $converter = new Converter();
            $familia = $converter->convertirFamiliaAJson($familia);
        }
        return $familia;
    }
}