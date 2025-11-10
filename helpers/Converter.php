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
            'email' => $alumno->getUsuario() ? $alumno->getUsuario()->getNombreUsuario() : null,
            'localidad_id' => $alumno->getUsuario() ? $alumno->getUsuario()->getLocalidadId() : null,
            'foto' => $alumno->getFoto64()
        ];
        return $alumnoArray;
    }

    public function convertirAlumnosAJson($alumnos)
    {
        $alumnosArray = [];
        
        foreach ($alumnos as $alumno) {
            $alumnosArray[] = $this->convertirAlumnoAJson($alumno);
        }
        return $alumnosArray;
    }
}