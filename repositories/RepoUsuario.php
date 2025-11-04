<?php
namespace repositories;

use PDO;
use Exception;
use models\Usuario;
use repositories\Connection;
use repositories\RepoToken;

class RepoUsuario
{
    public function save($usuario)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO usuario (nombre_usuario, password, rol_id, localidad_id)
                VALUES (:nombre_usuario, :password, :rol_id, :localidad_id)
            ");

            $stmt->bindValue(':nombre_usuario', $usuario->getNombreUsuario());
            $stmt->bindValue(':password', $usuario->getPasswordHash());
            $stmt->bindValue(':rol_id', $usuario->getRolId(), PDO::PARAM_INT);
            $stmt->bindValue(':localidad_id', $usuario->getLocalidadId(), PDO::PARAM_INT);

            $stmt->execute();
            $usuario->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar usuario: " . $e->getMessage());
            $usuario = null;
        }

        return $usuario;
    }

    public function findById($id, $loadTokens = false)
    {
        $usuario = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $usuario = $this->mapRowToUsuario($row);

                if ($loadTokens) {
                    $repoToken = new RepoToken();
                    $tokens = $repoToken->findByIdUsuario($usuario->getId());
                    $usuario->setTokens($tokens);
                }
            }
        } catch (Exception $e) {
            error_log("Error al buscar usuario por ID: " . $e->getMessage());
        }

        return $usuario;
    }

    public function findAll($loadTokens = false)
    {
        $usuarios = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM usuario ORDER BY id DESC");

            $repoToken = $loadTokens ? new RepoToken() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuario = $this->mapRowToUsuario($row);

                if ($loadTokens) {
                    $tokens = $repoToken->findByIdUsuario($usuario->getId());
                    $usuario->setTokens($tokens);
                }

                $usuarios[] = $usuario;
            }
        } catch (Exception $e) {
            error_log("Error al obtener todos los usuarios: " . $e->getMessage());
        }

        return $usuarios;
    }

    public function findByNombreUsuario($nombreUsuario, $loadTokens = false)
    {
        $usuario = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario");
            $stmt->bindValue(':nombre_usuario', $nombreUsuario);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $usuario = $this->mapRowToUsuario($row);

                if ($loadTokens) {
                    $repoToken = new RepoToken();
                    $tokens = $repoToken->findByIdUsuario($usuario->getId());
                    $usuario->setTokens($tokens);
                }
            }
        } catch (Exception $e) {
            error_log("Error al buscar usuario por nombre_usuario: " . $e->getMessage());
        }

        return $usuario;
    }

    public function update($usuario)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE usuario
                SET nombre_usuario = :nombre_usuario,
                    password = :password,
                    rol_id = :rol_id,
                    localidad_id = :localidad_id
                WHERE id = :id
            ");

            $stmt->bindValue(':nombre_usuario', $usuario->getNombreUsuario());
            $stmt->bindValue(':password', $usuario->getPasswordHash());
            $stmt->bindValue(':rol_id', $usuario->getRolId(), PDO::PARAM_INT);
            $stmt->bindValue(':localidad_id', $usuario->getLocalidadId(), PDO::PARAM_INT);
            $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            $usuario = null;
        }

        return $usuario;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM usuario WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
        }

        return $result;
    }

    /** 
     * 🔒 Método privado para mapear un array (fila SQL) a un objeto Usuario
     */
    private function mapRowToUsuario($row)
    {
        $usuario = new Usuario(
            $row['nombre_usuario'],
            $row['password'],
            $row['rol_id'],
            $row['localidad_id']
        );
        $usuario->setId($row['id']);

        return $usuario;
    }
}
