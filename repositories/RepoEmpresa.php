<?php
namespace repositories;

use PDO;
use Exception;
use models\Empresa;
use repositories\Connection;
use repositories\RepoUsuario;
use repositories\RepoOferta;

class RepoEmpresa
{
    public function save($empresa)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO empresa 
                (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, verificada, descripcion, usuario_id)
                VALUES (:nombre, :telefono, :direccion, :nombre_persona, :telefono_persona, :logo, :verificada, :descripcion, :usuario_id)
            ");

            $stmt->bindValue(':nombre', $empresa->getNombre());
            $stmt->bindValue(':telefono', $empresa->getTelefono());
            $stmt->bindValue(':direccion', $empresa->getDireccion());
            $stmt->bindValue(':nombre_persona', $empresa->getNombrePersona());
            $stmt->bindValue(':telefono_persona', $empresa->getTelefonoPersona());
            $stmt->bindValue(':logo', $empresa->getFoto());
            $stmt->bindValue(':verificada', $empresa->getVerificada(), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $empresa->getDescripcion());
            $stmt->bindValue(':usuario_id', $empresa->getUsuario() ? $empresa->getUsuario()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $empresa->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar empresa: " . $e->getMessage());
            $empresa = null;
        }

        return $empresa;
    }

    public function saveConConexion($empresa, $conn)
    {
        try {
            $stmt = $conn->prepare("
                INSERT INTO empresa 
                (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, verificada, descripcion, usuario_id)
                VALUES (:nombre, :telefono, :direccion, :nombre_persona, :telefono_persona, :logo, :verificada, :descripcion, :usuario_id)
            ");

            $stmt->bindValue(':nombre', $empresa->getNombre());
            $stmt->bindValue(':telefono', $empresa->getTelefono());
            $stmt->bindValue(':direccion', $empresa->getDireccion());
            $stmt->bindValue(':nombre_persona', $empresa->getNombrePersona());
            $stmt->bindValue(':telefono_persona', $empresa->getTelefonoPersona());
            $stmt->bindValue(':logo', $empresa->getFoto());
            $stmt->bindValue(':verificada', $empresa->getVerificada(), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $empresa->getDescripcion());
            $stmt->bindValue(':usuario_id', $empresa->getUsuario() ? $empresa->getUsuario()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $empresa->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar empresa: " . $e->getMessage());
            $empresa = null;
        }

        return $empresa;
    }
    
    public function findById($id, $loadUsuario = false, $loadOfertas = false)
    {
        $empresa = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM empresa WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
                $repoOferta  = $loadOfertas ? new RepoOferta()  : null;

                $empresa = $this->mapRowToEmpresa($row, $repoUsuario, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar empresa por ID: " . $e->getMessage());
        }

        return $empresa;
    }

    public function getByUsuarioId($usuarioId, $loadOfertas = false)
    {
        $empresa = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM empresa WHERE usuario_id = :usuario_id");
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoUsuario = new RepoUsuario();
                $repoOferta  = $loadOfertas ? new RepoOferta()  : null;

                $empresa = $this->mapRowToEmpresa($row, $repoUsuario, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar empresa por usuario ID: " . $e->getMessage());
        }

        return $empresa;
    }
    public function findAll($loadUsuario = false, $loadOfertas = false)
    {
        $empresas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM empresa ORDER BY id DESC");

            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
            $repoOferta  = $loadOfertas ? new RepoOferta()  : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $empresas[] = $this->mapRowToEmpresa($row, $repoUsuario, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todas las empresas: " . $e->getMessage());
        }

        return $empresas;
    }

    public function update($empresa, $conn = null)
    {
        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("
                UPDATE empresa
                SET nombre = :nombre,
                    telefono = :telefono,
                    direccion = :direccion,
                    nombre_persona = :nombre_persona,
                    telefono_persona = :telefono_persona,
                    logo = :logo,
                    verificada = :verificada,
                    descripcion = :descripcion
                WHERE id = :id
            ");

            $stmt->bindValue(':nombre', $empresa->getNombre());
            $stmt->bindValue(':telefono', $empresa->getTelefono());
            $stmt->bindValue(':direccion', $empresa->getDireccion());
            $stmt->bindValue(':nombre_persona', $empresa->getNombrePersona());
            $stmt->bindValue(':telefono_persona', $empresa->getTelefonoPersona());
            $stmt->bindValue(':logo', $empresa->getFoto());
            $stmt->bindValue(':verificada', $empresa->getVerificada(), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $empresa->getDescripcion());
            $stmt->bindValue(':id', $empresa->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar empresa: " . $e->getMessage());
            $empresa = null;
        }

        return $empresa;
    }

    public function delete($id, $conn = null)
    {
        $result = false;

        try {
            if ($conn === null) {
                $conn = Connection::getConnection();
            }
            $stmt = $conn->prepare("DELETE FROM empresa WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar empresa: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Mapea una fila de la base de datos a un objeto Empresa.
     */
    private function mapRowToEmpresa($row, $repoUsuario = null, $repoOferta = null)
    {
        $usuario = null;
        if ($repoUsuario ) {
            $usuario = $repoUsuario->findById($row['usuario_id']);
        }

        $empresa = new Empresa(
            $row['nombre'],
            $row['telefono'],
            $row['direccion'],
            $row['nombre_persona'],
            $row['telefono_persona'],
            $row['logo'],
            $row['verificada'],
            $row['descripcion'],
            $usuario
        );
        $empresa->setId($row['id']);

        if ($repoOferta) {
            $empresa->setOfertas($repoOferta->findByEmpresaId($row['id']));
        }

        return $empresa;
    }
}
