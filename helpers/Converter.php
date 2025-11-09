<?php
namespace helpers;
class Converter
{
    public function convertirAlumnoAJson($alumno)
    {
        $alumnoArray = [
            'respuesta' => true,
            'id' => $alumno->getId(),
            'nombre' => $alumno->getNombre(),
            'apellido' => $alumno->getApellido(),
            'telefono' => $alumno->getTelefono(),
            'direccion' => $alumno->getDireccion(),
            'foto' => $alumno->getFoto(),
            'cv' => $alumno->getCv(),
            'email' => $alumno->getUsuario() ? $alumno->getUsuario()->getNombreUsuario() : null,
        ];
        return $alumnoArray;
    }
}