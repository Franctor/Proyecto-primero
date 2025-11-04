<?php
namespace models;
class Familia
{
    private $id;
    private $nombre;
    private $ciclos;

    public function __construct($nombre, $ciclos = [])
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->ciclos = $ciclos;
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
    public function getCiclos()
    {
        return $this->ciclos;
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
    public function setCiclos($ciclos)
    {
        $this->ciclos = $ciclos;
    }
}
?>