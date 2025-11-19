<?php
namespace services;
use repositories\RepoSolicitud;
class SolicitudService
{
    private $repoSolicitud;

    public function __construct()
    {
        $this->repoSolicitud = new RepoSolicitud();
    }

    public function eliminarSolicitudesByOfertaId($ofertaId, $conn = null)
    {
        return $this->repoSolicitud->deleteByOfertaId($ofertaId, $conn);
    }
}