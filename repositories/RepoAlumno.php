<?php
namespace repositories;

use PDO;
use Exception;
use models\Alumno;
use repositories\RepoUsuario;
use repositories\RepoSolicitud;
use repositories\RepoCiclo;
use repositories\Connection;

class RepoAlumno
{
    public function save($alumno)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO alumno (nombre, apellido, telefono, direccion, foto, cv, activo, usuario_id)
                VALUES (:nombre, :apellido, :telefono, :direccion, :foto, :cv, :activo, :usuario_id)
            ");

            $stmt->bindValue(':nombre', $alumno->getNombre());
            $stmt->bindValue(':apellido', $alumno->getApellido());
            $stmt->bindValue(':telefono', $alumno->getTelefono());
            $stmt->bindValue(':direccion', $alumno->getDireccion());
            $stmt->bindValue(':foto', $alumno->getFoto());
            $stmt->bindValue(':cv', $alumno->getCv());
            $stmt->bindValue(':activo', $alumno->getActivo(), PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $alumno->getUsuario() ? $alumno->getUsuario()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $alumno->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar alumno: " . $e->getMessage());
            $alumno = null;
        }

        return $alumno;
    }

    public function saveConConexion($alumno, $conn)
    {
        try {
            $stmt = $conn->prepare("
                INSERT INTO alumno (nombre, apellido, telefono, direccion, foto, cv, activo, usuario_id)
                VALUES (:nombre, :apellido, :telefono, :direccion, :foto, :cv, :activo, :usuario_id)
            ");

            $stmt->bindValue(':nombre', $alumno->getNombre());
            $stmt->bindValue(':apellido', $alumno->getApellido());
            $stmt->bindValue(':telefono', $alumno->getTelefono());
            $stmt->bindValue(':direccion', $alumno->getDireccion());
            $stmt->bindValue(':foto', $alumno->getFoto());
            $stmt->bindValue(':cv', $alumno->getCv());
            $stmt->bindValue(':activo', $alumno->getActivo(), PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $alumno->getUsuario() ? $alumno->getUsuario()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $alumno->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar alumno con conexión: " . $e->getMessage());
            $alumno = null;
        }

        return $alumno;
    }

    public function findById($id, $loadUsuario = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $alumno = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM alumno WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
                $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
                $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

                $alumno = $this->mapRowToAlumno($row, $repoUsuario, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al buscar alumno por ID: " . $e->getMessage());
        }

        return $alumno;
    }
    public function findByCicloId($cicloId, $loadUsuario = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $alumnos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
            SELECT a.*
            FROM alumno a
            INNER JOIN alumnos_ciclos ac ON a.id = ac.alumno_id
            WHERE ac.ciclo_id = :cicloId
        ");
            $stmt->bindValue(':cicloId', $cicloId, PDO::PARAM_INT);
            $stmt->execute();

            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $alumnos[] = $this->mapRowToAlumno($row, $repoUsuario, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener alumnos por ciclo ID: " . $e->getMessage());
        }

        return $alumnos;
    }


    public function findAll($loadUsuario = false, $loadSolicitudes = false, $loadCiclos = false)
    {
        $alumnos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM alumno ORDER BY id DESC");

            // Solo se crean una vez los repos que se necesiten
            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
            $repoSolicitud = $loadSolicitudes ? new RepoSolicitud() : null;
            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $alumnos[] = $this->mapRowToAlumno($row, $repoUsuario, $repoSolicitud, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todos los alumnos: " . $e->getMessage());
        }

        return $alumnos;
    }

    public function update($alumno)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE alumno
                SET nombre = :nombre,
                    apellido = :apellido,
                    telefono = :telefono,
                    direccion = :direccion,
                    foto = :foto,
                    cv = :cv,
                    activo = :activo
                WHERE id = :id
            ");

            $stmt->bindValue(':nombre', $alumno->getNombre());
            $stmt->bindValue(':apellido', $alumno->getApellido());
            $stmt->bindValue(':telefono', $alumno->getTelefono());
            $stmt->bindValue(':direccion', $alumno->getDireccion());
            $stmt->bindValue(':foto', $alumno->getFoto());
            $stmt->bindValue(':cv', $alumno->getCv());
            $stmt->bindValue(':activo', $alumno->getActivo(), PDO::PARAM_INT);
            $stmt->bindValue(':id', $alumno->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar alumno: " . $e->getMessage());
            $alumno = null;
        }

        return $alumno;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM alumno WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar alumno: " . $e->getMessage());
        }

        return $result;
    }

    public function deleteCiclosByAlumnoId($alumnoId)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM alumnos_ciclos WHERE alumno_id = :alumno_id");
            $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar ciclos del alumno: " . $e->getMessage());
        }

        return $result;
    }
    /**
     * Mapea una fila de la base de datos a un objeto Alumno.
     */
    private function mapRowToAlumno($row, $repoUsuario = null, $repoSolicitud = null, $repoCiclo = null)
    {
        $usuario = null;
        if ($repoUsuario) {
            $usuario = $repoUsuario->findById($row['usuario_id']);
        }

        $alumno = new Alumno(
            $row['nombre'],
            $row['apellido'],
            $row['telefono'],
            $row['direccion'],
            $row['foto'],
            $row['cv'],
            $row['activo'],
            $usuario
        );
        $alumno->setId($row['id']);

        if ($repoSolicitud) {
            $alumno->setSolicitudes($repoSolicitud->findByAlumnoId($row['id']));
        }

        if ($repoCiclo) {
            $alumno->setCiclos($repoCiclo->findByAlumnoId($row['id']));
        }

        return $alumno;
    }
}
?>