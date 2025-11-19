<?php
namespace services;
use DateTime;
use repositories\RepoSolicitud;
use services\OfertaService;
use models\Solicitud;
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

    public function aplicarOferta($ofertaId, $alumnoId)
    {
        $ofertaService = new OfertaService();
        $alumnoService = new AlumnoService();
        $oferta = $ofertaService->getOfertaById($ofertaId);
        $alumno = $alumnoService->getAlumnoById($alumnoId);
        $solicitud = new Solicitud(
            new DateTime('today'),
            0,
            $alumno,
            $oferta
        );;
        return $this->repoSolicitud->save($solicitud);
    }

    public function desaplicarOferta($solicitudId)
    {
        return $this->repoSolicitud->delete($solicitudId);
    }
    public function getSolicitudById($solicitudId)
    {
        return $this->repoSolicitud->findById($solicitudId);
    }

    //METODOS PARA API
    public function getSolicitudByIdAPI($solicitudId)
    {
        return $this->repoSolicitud->findById($solicitudId,false,true);
    }

    public function getSolicitudesByAlumnoIdAPI($alumnoId)
    {
        return $this->repoSolicitud->findByAlumnoId($alumnoId, false, true);
    }


    

    
}