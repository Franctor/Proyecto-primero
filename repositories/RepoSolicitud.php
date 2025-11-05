<?php
namespace repositories;

use PDO;
use Exception;
use models\Solicitud;
use repositories\RepoAlumno;
use repositories\Connection;
use repositories\RepoOferta;

class RepoSolicitud
{
    public function save($solicitud)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO solicitud (fecha_solicitud, finalizado, alumno_id, oferta_id)
                VALUES (:fecha_solicitud, :finalizado, :alumno_id, :oferta_id)
            ");

            $stmt->bindValue(':fecha_solicitud', $solicitud->getFechaSolicitud());
            $stmt->bindValue(':finalizado', $solicitud->getFinalizado());
            $stmt->bindValue(':alumno_id', $solicitud->getAlumno() ? $solicitud->getAlumno()->getId() : null, PDO::PARAM_INT);
            $stmt->bindValue(':oferta_id', $solicitud->getOferta() ? $solicitud->getOferta()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $solicitud->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar solicitud: " . $e->getMessage());
            $solicitud = null;
        }

        return $solicitud;
    }

    public function findById($id, $loadAlumno = false, $loadOferta = false)
    {
        $solicitud = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM solicitud WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoAlumno = $loadAlumno ? new RepoAlumno() : null;
                $repoOferta = $loadOferta ? new RepoOferta() : null;

                $solicitud = $this->mapRowToSolicitud($row, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar solicitud por ID: " . $e->getMessage());
        }

        return $solicitud;
    }

    public function findByAlumnoId($alumnoId, $loadAlumno = false, $loadOferta = false)
    {
        $solicitudes = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM solicitud WHERE alumno_id = :alumno_id");
            $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $stmt->execute();

            $repoAlumno = $loadAlumno ? new RepoAlumno() : null;
            $repoOferta = $loadOferta ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $solicitudes[] = $this->mapRowToSolicitud($row, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar solicitudes por ID de alumno: " . $e->getMessage());
        }

        return $solicitudes;
    }

    public function findAll($loadAlumno = false, $loadOferta = false)
    {
        $solicitudes = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM solicitud ORDER BY id DESC");

            $repoAlumno = $loadAlumno ? new RepoAlumno() : null;
            $repoOferta = $loadOferta ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $solicitudes[] = $this->mapRowToSolicitud($row, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar todas las solicitudes: " . $e->getMessage());
        }

        return $solicitudes;
    }

    public function update($solicitud)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE solicitud
                SET fecha_solicitud = :fecha_solicitud,
                    finalizado = :finalizado,
                    alumno_id = :alumno_id,
                    oferta_id = :oferta_id
                WHERE id = :id
            ");

            $stmt->bindValue(':fecha_solicitud', $solicitud->getFechaSolicitud());
            $stmt->bindValue(':finalizado', $solicitud->getFinalizado());
            $stmt->bindValue(':alumno_id', $solicitud->getAlumno() ? $solicitud->getAlumno()->getId() : null, PDO::PARAM_INT);
            $stmt->bindValue(':oferta_id', $solicitud->getOferta() ? $solicitud->getOferta()->getId() : null, PDO::PARAM_INT);
            $stmt->bindValue(':id', $solicitud->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar solicitud: " . $e->getMessage());
        }

        return $solicitud;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM solicitud WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar solicitud: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Mapea una fila de la base de datos a un objeto Solicitud.
     */
    private function mapRowToSolicitud($row, $repoAlumno = null, $repoOferta = null)
    {
        $alumno = null;
        $oferta = null;

        if ($repoAlumno) {
            $alumno = $repoAlumno->findById($row['alumno_id']);
        }

        if ($repoOferta) {
            $oferta = $repoOferta->findById($row['oferta_id']);
        }

        $solicitud = new Solicitud(
            $row['fecha_solicitud'],
            $row['finalizado'],
            $alumno,
            $oferta
        );
        $solicitud->setId($row['id']);

        return $solicitud;
    }
}
?>
