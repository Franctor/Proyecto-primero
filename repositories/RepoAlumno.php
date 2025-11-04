<?php
namespace repositories;
use models\Alumno;
class RepoAlumno {

    public function save(Alumno $alumno) {
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
            $stmt->bindValue(':usuario_id', $alumno->getUsuario()->getId(), PDO::PARAM_INT);

            $stmt->execute();
            $alumno->setId($conn->lastInsertId());

        } catch (Exception $e) {
            error_log("Error al guardar alumno: " . $e->getMessage());
            $alumno = null;
        }

        return $alumno;
    }

    public function findById($id) {
        $alumno = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM alumno WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Se asume que RepoUsuario existe y tiene findById
                $repoUsuario = new RepoUsuario();
                $usuario = $repoUsuario->findById($row['usuario_id']);

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
            }
        } catch (Exception $e) {
            error_log("Error al buscar alumno por ID: " . $e->getMessage());
        }

        return $alumno;
    }

    public function findAll() {
        $alumnos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM alumno ORDER BY id DESC");

            $repoUsuario = new RepoUsuario();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuario = $repoUsuario->findById($row['usuario_id']);

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
                $alumnos[] = $alumno;
            }
        } catch (Exception $e) {
            error_log("Error al obtener todos los alumnos: " . $e->getMessage());
        }

        return $alumnos;
    }

    public function update(Alumno $alumno) {
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

    public function delete($id) {
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
}
?>
