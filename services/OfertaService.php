<?php
namespace services;
use DateTime;
use Exception;
use repositories\RepoOferta;
use repositories\Connection;
use models\Oferta;
class OfertaService
{
    private $repoOferta;
    public function __construct()
    {
        $this->repoOferta = new RepoOferta();
    }
    public function getOfertasActivasByEmpresaId($empresaId)
    {
        $resultado = $this->repoOferta->findActiveByEmpresaId($empresaId);
        return $resultado;
    }

    public function getOfertasPasadasByEmpresaId($empresaId)
    {
        $resultado = $this->repoOferta->findPastByEmpresaId($empresaId);
        return $resultado;
    }

    public function crearOferta($data, $empresaId)
    {
        $fecha_oferta = new DateTime('today');
        $fecha_fiin_oferta = $data['fecha_fiin_oferta'];
        $titulo = trim($data['titulo']);
        $descripcion = trim($data['descripcion']);
        $ciclosSeleccionados = $data['ciclosSeleccionados'] ?? [];

        $empresaService = new EmpresaService();
        $empresa = $empresaService->getEmpresaById($empresaId);
        $oferta = new Oferta(
            $fecha_oferta,
            $fecha_fiin_oferta,
            $descripcion,
            $titulo,
            $empresa
        );
        return $this->guardarOferta($oferta, $ciclosSeleccionados);
    }

    private function guardarOferta($oferta, $ciclosSeleccionados)
    {
        $ok = false;
        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            // Guardar la oferta
            $this->repoOferta->save($oferta, $conn);

            // Guardar en la tabla ofertas_ciclos las asociaciones con los ciclos seleccionados
            if (!empty($ciclosSeleccionados)) {
                foreach ($ciclosSeleccionados as $cicloId) {
                    $this->repoOferta->saveOfertaCiclo($oferta->getId(), $cicloId, $conn);
                }
            }

            $conn->commit();
            $ok = true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error al eliminar oferta: " . $e->getMessage());
        }

        return $ok;
    }

    public function getOfertaById($ofertaId)
    {
        return $this->repoOferta->findById($ofertaId, true, false, true);
    }

    public function editarOferta($ofertaId, $data)
    {
        $resultado = null;

        $oferta = $this->getOfertaById($ofertaId);
        if ($oferta) {
            $fecha_oferta = new DateTime('today');
            $fecha_fiin_oferta = $data['fecha_fiin_oferta'];
            $titulo = trim($data['titulo']);
            $descripcion = trim($data['descripcion']);
            $ciclosSeleccionados = $data['ciclosSeleccionados'] ?? []; //ME HE QUEDADO POR AQUI, HAY QUE ACTUALIZAR LOS CICLOS ASOCIADOS Y HACER TRANSACTION

            $oferta->setFechaInicio($fecha_oferta);
            $oferta->setFechaFin($fecha_fiin_oferta);
            $oferta->setTitulo($titulo);
            $oferta->setDescripcion($descripcion);

            $resultado = $this->repoOferta->update($oferta);
        }
        return $resultado;
    }

    public function eliminarOferta($ofertaId)
    {
        $ok = false;
        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();


            $solicitudService = new SolicitudService();

            $solicitudService->eliminarSolicitudesByOfertaId($ofertaId, $conn);

            $this->repoOferta->deleteOfertasCiclosByOfertaId($ofertaId, $conn);

            $this->repoOferta->delete($ofertaId, $conn);

            $conn->commit();
            $ok = true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error al eliminar oferta: " . $e->getMessage());
        }

        return $ok;
    }
}

