<?php
namespace models;
class Empresa
{
    private $id;
    private $nombre;
    private $telefono;
    private $direccion;
    private $nombre_persona;
    private $telefono_persona;
    private $logo;
    private $verificada;
    private $descripcion;
    private $usuario;
    private $ofertas;

    public function __construct($nombre, $telefono, $direccion, $nombre_persona, $telefono_persona, $logo, $verificada=0, $descripcion, $usuario = null, $ofertas = [])
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->nombre_persona = $nombre_persona;
        $this->telefono_persona = $telefono_persona;
        $this->logo = $logo;
        $this->verificada = $verificada;
        $this->descripcion = $descripcion;
        $this->usuario = $usuario;
        $this->ofertas = $ofertas;
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
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getNombrePersona()
    {
        return $this->nombre_persona;
    }
    public function getTelefonoPersona()
    {
        return $this->telefono_persona;
    }
    public function getFoto()
    {
        return $this->logo;
    }
    public function getVerificada()
    {
        return $this->verificada;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getOfertas()
    {
        return $this->ofertas;
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
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setNombrePersona($nombre_persona)
    {
        $this->nombre_persona = $nombre_persona;
    }
    public function setTelefonoPersona($telefono_persona)
    {
        $this->telefono_persona = $telefono_persona;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    public function setVerificada($verificada)
    {
        $this->verificada = $verificada;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setOfertas($ofertas)
    {
        $this->ofertas = $ofertas;
    }
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }
    public function addOferta($oferta)
    {
        $this->ofertas[] = $oferta;
    }

}
?>