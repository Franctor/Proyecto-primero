<?php
namespace repositories;

use DateTime;
use PDO;
use Exception;
use models\Oferta;
use repositories\Connection;
use repositories\RepoEmpresa;
use repositories\RepoSolicitud;
use repositories\RepoCiclo;
class RepoOferta
{
    public function save($oferta, $conn = null)
    {
        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("
            INSERT INTO oferta 
            (fecha_oferta, fecha_fiin_oferta, descripcion, titulo, empresa_id)
            VALUES (:fecha_oferta, :fecha_fin_oferta, :descripcion, :titulo, :empresa_id)
        ");

            // Convertir DateTime a string en formato 'Y-m-d'
            $fechaInicio = $oferta->getFechaInicio();
            $fechaFin = $oferta->getFechaFin();

            $stmt->bindValue(
                ':fecha_oferta',
                $fechaInicio instanceof DateTime ? $fechaInicio->format('Y-m-d') : $fechaInicio
            );
            $stmt->bindValue(
                ':fecha_fin_oferta',
                $fechaFin instanceof DateTime ? $fechaFin->format('Y-m-d') : $fechaFin
            );
            $stmt->bindValue(':descripcion', $oferta->getDescripcion());
            $stmt->bindValue(':titulo', $oferta->getTitulo());
            $stmt->bindValue(':empresa_id', $oferta->getEmpresa()->getId(), PDO::PARAM_INT);

            $stmt->execute();
            $oferta->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar oferta: " . $e->getMessage());
            $oferta = null;
        }

        return $oferta;
    }

    public function saveOfertaCiclo($ofertaId, $cicloId, $conn = null)
    {
        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("
                INSERT INTO ofertas_ciclos 
                (oferta_id, ciclo_id, required)
                VALUES (:oferta_id, :ciclo_id, 1)
            ");

            $stmt->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
            $stmt->bindValue(':ciclo_id', $cicloId, PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al guardar oferta_ciclo: " . $e->getMessage());
        }
    }

    public function findById($id, $loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $oferta = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM oferta WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
                $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
                $repoCiclo = $loadCiclos ? new RepoCiclo() : null;
                $oferta = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al buscar oferta por ID: " . $e->getMessage());
        }

        return $oferta;
    }

    public function findAll($loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $ofertas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM oferta ORDER BY id DESC");

            $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ofertas[] = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todas las ofertas: " . $e->getMessage());
        }
        return $ofertas;
    }

    public function update($oferta, $conn = null)
    {
        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("
                UPDATE oferta
                SET fecha_oferta = :fecha_oferta,
                    fecha_fiin_oferta = :fecha_fiin_oferta,
                    descripcion = :descripcion,
                    titulo = :titulo
                WHERE id = :id
            ");

            // Convertir DateTime a string en formato 'Y-m-d'
            $fechaInicio = $oferta->getFechaInicio();
            $fechaFin = $oferta->getFechaFin();
            $stmt->bindValue(
                ':fecha_oferta',
                $fechaInicio instanceof DateTime ? $fechaInicio->format('Y-m-d') : $fechaInicio
            );
            $stmt->bindValue(
                ':fecha_fiin_oferta',
                $fechaFin instanceof DateTime ? $fechaFin->format('Y-m-d') : $fechaFin
            );
            $stmt->bindValue(':descripcion', $oferta->getDescripcion());
            $stmt->bindValue(':titulo', $oferta->getTitulo());
            $stmt->bindValue(':id', $oferta->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar oferta: " . $e->getMessage());
            $oferta = null;
        }

        return $oferta;
    }

    public function delete($id, $conn = null)
    {
        $result = false;

        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("DELETE FROM oferta WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar oferta: " . $e->getMessage());
        }

        return $result;
    }

    public function deleteByEmpresaId($empresaId, $conn = null)
    {
        $result = false;

        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("DELETE FROM oferta WHERE empresa_id = :empresa_id");
            $stmt->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar ofertas por empresa ID: " . $e->getMessage());
        }

        return $result;
    }

    public function deleteOfertasCiclosByOfertaId($ofertaId, $conn = null)
    {
        $result = false;

        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("DELETE FROM ofertas_ciclos WHERE oferta_id = :oferta_id");
            $stmt->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar ofertas_ciclos por oferta ID: " . $e->getMessage());
        }

        return $result;
    }

    public function findByEmpresaId($empresaId, $loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $ofertas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM oferta WHERE empresa_id = :empresa_id ORDER BY id DESC");
            $stmt->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmt->execute();

            $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ofertas[] = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener ofertas por empresa ID: " . $e->getMessage());
        }

        return $ofertas;
    }
    public function findByCicloId($cicloId, $loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $ofertas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
            SELECT o.*, oc.required
            FROM oferta o
            INNER JOIN ofertas_ciclos oc ON o.id = oc.oferta_id
            WHERE oc.ciclo_id = :cicloId
        ");
            $stmt->bindValue(':cicloId', $cicloId, PDO::PARAM_INT);
            $stmt->execute();

            $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $oferta = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);

                $ofertas[] = $oferta;
            }
        } catch (Exception $e) {
            error_log("Error al obtener ofertas por ciclo ID: " . $e->getMessage());
        }

        return $ofertas;
    }

    public function findActiveByEmpresaId($empresaId, $loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $ofertas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM oferta WHERE empresa_id = :empresa_id AND fecha_fiin_oferta >= CURDATE() ORDER BY id DESC");
            $stmt->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmt->execute();

            $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ofertas[] = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener ofertas activas por empresa ID: " . $e->getMessage());
        }

        return $ofertas;
    }

    public function findPastByEmpresaId($empresaId, $loadEmpresa = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $ofertas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM oferta WHERE empresa_id = :empresa_id AND fecha_fiin_oferta < CURDATE() ORDER BY id DESC");
            $stmt->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmt->execute();

            $repoEmpresa = $loadEmpresa ? new RepoEmpresa() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ofertas[] = $this->mapRowToOferta($row, $repoEmpresa, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener ofertas pasadas por empresa ID: " . $e->getMessage());
        }

        return $ofertas;
    }

    /**
     * Mapea una fila de la base de datos a un objeto Oferta.
     */
    private function mapRowToOferta($row, $repoEmpresa = null, $repoSolicitud = null, $repoCiclo = null)
    {
        $empresa = null;
        if ($repoEmpresa) {
            $empresa = $repoEmpresa->findById($row['empresa_id']);
        }

        $fecha = new DateTime($row['fecha_oferta']);
        $fecha_fin = new DateTime($row['fecha_fiin_oferta']);
        $oferta = new Oferta(
            $fecha,
            $fecha_fin,
            $row['descripcion'],
            $row['titulo'],
            $empresa
        );
        $oferta->setId($row['id']);

        if ($repoSolicitud) {
            $oferta->setSolicitudes($repoSolicitud->findByOfertaId($row['id']));
        }

        if ($repoCiclo) {
            $oferta->setCiclos($repoCiclo->findByOfertaId($row['id']));
        }

        return $oferta;
    }
}