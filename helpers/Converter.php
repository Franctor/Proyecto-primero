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

    public function convertirSolicitudesAJson($solicitudes)
    {
        $solicitudesArray = [];

        foreach ($solicitudes as $solicitud) {
            $solicitudesArray[] = $this->convertirSolicitudAJson($solicitud);
        }
        return $solicitudesArray;
    }

    public function convertirSolicitudAJson($solicitud)
    {
        $solicitudArray = [
            'id' => $solicitud->getId(),
            'fecha_solicitud' => $this->formatearFecha($solicitud->getFechaSolicitud()),
            'oferta' => $this->convertirOfertaAJson($solicitud->getOferta())
        ];
        return $solicitudArray;
    }

    public function convertirOfertaAJson($oferta)
    {
        $ofertaArray = [
            'id' => $oferta->getId(),
            'titulo' => $oferta->getTitulo(),
            'descripcion' => $oferta->getDescripcion(),
            'fecha_inicio' => $this->formatearFecha($oferta->getFechaInicio()),
            'fecha_fin' => $this->formatearFecha($oferta->getFechaFin()),
            'empresa' => $this->convertirEmpresaAJson($oferta->getEmpresa())
        ];
        return $ofertaArray;
    }

    public function convertirEmpresaAJson($empresa)
    {
        $empresaArray = [
            'id' => $empresa->getId(),
            'nombre' => $empresa->getNombre(),
            'descripcion' => $empresa->getDescripcion(),
            'direccion' => $empresa->getDireccion(),
            'telefono' => $empresa->getTelefono(),
            'logo' => $empresa->getFoto64()
        ];
        return $empresaArray;
    }

    private function formatearFecha($fecha)
    {
        if ($fecha instanceof \DateTimeInterface) {
            return $fecha->format('d/m/Y');
        }
        return $fecha;
    }

    public function convertirOfertasAJson($ofertas)
    {
        $ofertasArray = [];

        foreach ($ofertas as $oferta) {
            $ofertasArray[] = $this->convertirOfertaAJsonDos($oferta);
        }
        return $ofertasArray;
    }

    public function convertirOfertaAJsonDos($oferta)
    {
        $ofertaArray = [
            'id' => $oferta->getId(),
            'titulo' => $oferta->getTitulo(),
            'descripcion' => $oferta->getDescripcion(),
            'fecha_inicio' => $this->formatearFecha($oferta->getFechaInicio()),
            'fecha_fin' => $this->formatearFecha($oferta->getFechaFin()),
            'solicitudes'=> $oferta->getSolicitudes() ? $this->convertirSolicitudesAJsonDos($oferta->getSolicitudes()) : 'false'
        ];
        return $ofertaArray;
    }

    public function convertirSolicitudesAJsonDos($solicitudes)
    {
        $solicitudesArray = [];

        foreach ($solicitudes as $solicitud) {
            $solicitudesArray[] = [
                'id' => $solicitud->getId(),
                'fecha_solicitud' => $this->formatearFecha($solicitud->getFechaSolicitud()),
                'estado' => $solicitud->getEstado(),
                'alumno'=> $solicitud->getAlumno() ? $this->convertirAlumnoAJsonDos($solicitud->getAlumno()) : 'false'
            ];
        }
        return $solicitudesArray;
    }

    public function convertirAlumnoAJsonDos($alumno)
    {
        $alumnoArray = [
            'id' => $alumno->getId(),
            'nombre' => $alumno->getNombre(),
            'apellido' => $alumno->getApellido(),
            'telefono' => $alumno->getTelefono(),
            'direccion' => $alumno->getDireccion(),
            'foto' => $alumno->getFoto64()
        ];
        return $alumnoArray;
    }
}