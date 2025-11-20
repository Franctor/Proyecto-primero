<?php
namespace models;
class Solicitud
{
    private $id;
    private $fecha_solicitud;
    private $finalizado;
    private $alumno;
    private $oferta;

    public function __construct($fecha_solicitud, $finalizado, $alumno = null, $oferta = null)
    {
        $this->id = null;
        $this->fecha_solicitud = $fecha_solicitud;
        $this->finalizado = $finalizado;
        $this->alumno = $alumno;
        $this->oferta = $oferta;
    }

    // --- Getters ---
    public function getId()
    {
        return $this->id;
    }
    public function getFechaSolicitud()
    {
        return $this->fecha_solicitud;
    }
    public function getEstado()
    {
        return $this->finalizado;
    }
    public function getAlumno()
    {
        return $this->alumno;
    }
    public function getOferta()
    {
        return $this->oferta;
    }
    // --- Setters ---
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setFechaSolicitud($fecha_solicitud)
    {
        $this->fecha_solicitud = $fecha_solicitud;
    }
    public function setEstado($finalizado)
    {
        $this->finalizado = $finalizado;
    }
    public function setAlumno($alumno)
    {
        $this->alumno = $alumno;
    }
    public function setOferta($oferta)
    {
        $this->oferta = $oferta;
    }
}
?>