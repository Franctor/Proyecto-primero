<?php
namespace models;
class Oferta {
    private $id;
    private $fecha_inicio;
    private $fecha_fin;
    private $descripcion;
    private $titulo;
    private $empresa;
    private $solicitudes;
    private $ciclos;

    

    // Constructor
    public function __construct($fecha_inicio, $fecha_fin, $descripcion, $titulo, $empresa = null, $solicitudes = [], $ciclos = []) {
        $this->id = null;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->descripcion = $descripcion;
        $this->titulo = $titulo;
        $this->empresa = $empresa;
        $this->solicitudes = $solicitudes;
        $this->ciclos = $ciclos;
    }

    // --- Getters ---
    public function getId() {
        return $this->id;
    }

    public function getFechaInicio() {
        return $this->fecha_inicio;
    }

    public function getFechaFin() {
        return $this->fecha_fin;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function getSolicitudes() {
        return $this->solicitudes;
    }

    public function getCiclos() {
        return $this->ciclos;
    }
    public function getTitulo() {
        return $this->titulo;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }

    // --- Setters ---
    public function setId($id) {
        $this->id = $id;
    }

    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function setSolicitudes($solicitudes) {
        $this->solicitudes = $solicitudes;
    }

    public function setCiclos($ciclos) {
        $this->ciclos = $ciclos;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    // --- Métodos de gestión ---
    public function addSolicitud($solicitud) {
        $this->solicitudes[] = $solicitud;
    }
    public function addCiclo($ciclo) {
        $this->ciclos[] = $ciclo;
    } 
}
?>
