<?php
namespace repositories;

use PDO;
use Exception;
use models\Oferta;
use repositories\Connection;
use repositories\RepoEmpresa;
use repositories\RepoSolicitud;
use repositories\RepoCiclo;
class RepoOferta
{
    public function save($oferta)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO oferta 
                (fecha_oferta, fecha_fiin_oferta, empresa_id)
                VALUES (:fecha_oferta, :fecha_fin_oferta, :empresa_id)
            ");

            $stmt->bindValue(':fecha_oferta', $oferta->getFechaInicio());
            $stmt->bindValue(':fecha_fin_oferta', $oferta->getFechaFin());
            $stmt->bindValue(':empresa_id', $oferta->getEmpresa()->getId(), PDO::PARAM_INT);

            $stmt->execute();
            $oferta->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar oferta: " . $e->getMessage());
            $oferta = null;
        }

        return $oferta;
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

    public function update($oferta)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE oferta
                SET fecha_oferta = :fecha_oferta,
                    fecha_fiin_oferta = :fecha_fin_oferta
                WHERE id = :id
            ");

            $stmt->bindValue(':fecha_oferta', $oferta->getFechaInicio());
            $stmt->bindValue(':fecha_fin_oferta', $oferta->getFechaFin());
            $stmt->bindValue(':id', $oferta->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar oferta: " . $e->getMessage());
            $oferta = null;
        }

        return $oferta;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM oferta WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar oferta: " . $e->getMessage());
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

    /**
     * Mapea una fila de la base de datos a un objeto Oferta.
     */
    private function mapRowToOferta($row, $repoEmpresa = null, $repoSolicitud = null, $repoCiclo = null)
    {
        $empresa = null;
        if ($repoEmpresa ) {
            $empresa = $repoEmpresa->findById($row['empresa_id']);
        }

        $oferta = new Oferta(
            $row['fecha_oferta'],
            $row['fecha_fiin_oferta'],
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