<?php
namespace models;
class Ciclo
{
    private $id;
    private $nombre;
    private $nivel;
    private $alumnos;
    private $ofertas;
    private $familia;

    public function __construct($nombre, $nivel, $alumnos = [], $ofertas = [], $familia = null)
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->alumnos = $alumnos;
        $this->ofertas = $ofertas;
        $this->familia = $familia;
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

    public function getNivel()
    {
        return $this->nivel;
    }
    public function getAlumnos()
    {
        return $this->alumnos;
    }
    public function getOfertas()
    {
        return $this->ofertas;
    }
    public function getFamilia()
    {
        return $this->familia;
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

    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }
    public function setAlumnos($alumnos)
    {
        $this->alumnos = $alumnos;
    }

    public function setOfertas($ofertas)
    {
        $this->ofertas = $ofertas;
    }
    public function setFamilia($familia)
    {
        $this->familia = $familia;
    }
}
?>