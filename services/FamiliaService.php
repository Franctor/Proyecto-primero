<?php
namespace services;
use repositories\RepoFamilia;
use helpers\Converter;
class FamiliaService
{
    private $repoFamilia;
    public function __construct()
    {
        $this->repoFamilia = new RepoFamilia();
    }
    public function getFamilias()
    {
        $familias = $this->repoFamilia->findAll(true);
        if (!empty($familias)) {
            $converter = new Converter();
            $familias = $converter->convertirFamiliasAJson($familias);
        }
        return $familias;
    }

    public function getFamilia($idFamilia)
    {
        $familia = $this->repoFamilia->findById($idFamilia, true);
        if ($familia) {
            $converter = new Converter();
            $familia = $converter->convertirFamiliaAJson($familia);
        }
        return $familia;
    }
}