<?php
namespace models;
class Alumno
{
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $direccion;
    private $foto;
    private $cv;
    private $activo;
    private $usuario;
    private $ciclos;
    private $solicitudes;

    public function __construct($nombre, $apellido, $telefono, $direccion, $foto, $cv, $activo, $usuario = null, $ciclos = [], $solicitudes = [])
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->foto = $foto;
        $this->cv = $cv;
        $this->activo = $activo;
        $this->usuario = $usuario;
        $this->ciclos = $ciclos;
        $this->solicitudes = $solicitudes;
    }

    // --- Getters ---
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getFoto()
    {
        return $this->foto;
    }
    public function getCv()
    {
        return $this->cv;
    }
    public function getActivo()
    {
        return $this->activo;
    }
    public function getCiclos()
    {
        return $this->ciclos;
    }
    public function getSolicitudes()
    {
        return $this->solicitudes;
    }

    // --- Setters ---
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function setCv($cv)
    {
        $this->cv = $cv;
    }
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }
    public function addCiclo($ciclo)
    {
        $this->ciclos[] = $ciclo;
    }
    public function addSolicitud($solicitud)
    {
        $this->solicitudes[] = $solicitud;
    }
}
?>