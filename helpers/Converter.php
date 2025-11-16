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
            'foto' => $alumno->getFoto64(),
            'ciclos' => $this->convertirCiclosAJson($alumno->getCiclos())
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

    public function convertirCicloAJson($ciclo)
    {
        $cicloArray = [
            'id' => $ciclo->getId(),
            'nombre' => $ciclo->getNombre(),
            'familia_id' => $ciclo->getFamiliaId()
        ];
        return $cicloArray;
    }

    function convertirCiclosAJson($ciclos)
    {
        $ciclosArray = [];
        
        foreach ($ciclos as $ciclo) {
            $ciclosArray[] = $this->convertirCicloAJson($ciclo);
        }
        return $ciclosArray;
    }

    public function convertirFamiliaAJson($familia)
    {
        $familiaArray = [
            'id' => $familia->getId(),
            'nombre' => $familia->getNombre(),
            'ciclos' => $this->convertirCiclosAJson($familia->getCiclos())
        ];
        return $familiaArray;
    }

    public function convertirFamiliasAJson($familias)
    {
        $familiasArray = [];
        
        foreach ($familias as $familia) {
            $familiasArray[] = $this->convertirFamiliaAJson($familia);
        }
        return $familiasArray;
    }
}