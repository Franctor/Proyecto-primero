<?php
namespace repositories;

use PDO;
use Exception;
use models\Token;
use repositories\Connection;
use repositories\RepoUsuario;

class RepoToken
{
    public function save($token)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO token (valor, usuario_id)
                VALUES (:valor, :usuario_id)
            ");

            $stmt->bindValue(':valor', $token->getValor());
            $stmt->bindValue(
                ':usuario_id',
                $token->getUsuario() ? $token->getUsuario()->getId() : null,
                PDO::PARAM_INT
            );

            $stmt->execute();
            $token->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar token: " . $e->getMessage());
            $token = null;
        }

        return $token;
    }

    public function findById($id, $loadUsuario = true)
    {
        $token = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM token WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;
            if ($row) {
                $token = $this->mapRowToToken($row, $repoUsuario);
            }
        } catch (Exception $e) {
            error_log("Error al buscar token por ID: " . $e->getMessage());
        }

        return $token;
    }

    public function findAll($loadUsuario = false)
    {
        $tokens = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM token ORDER BY id DESC");

            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tokens[] = $this->mapRowToToken($row, $repoUsuario);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todos los tokens: " . $e->getMessage());
        }

        return $tokens;
    }

    public function findByIdUsuario($usuarioId, $loadUsuario = false)
    {
        $tokens = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM token WHERE usuario_id = :usuario_id");
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();

            $repoUsuario = $loadUsuario ? new RepoUsuario() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tokens[] = $this->mapRowToToken($row, $repoUsuario);
            }
        } catch (Exception $e) {
            error_log("Error al buscar tokens por ID de usuario: " . $e->getMessage());
        }

        return $tokens;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM token WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar token: " . $e->getMessage());
        }

        return $result;
    }
    public function deleteByUsuarioId($usuarioId)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM token WHERE usuario_id = :usuario_id");
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar tokens por ID de usuario: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Mapea una fila SQL a un objeto Token
     */
    private function mapRowToToken($row, $repoUsuario = null)
    {
        $usuario = null;

        if($repoUsuario){
            $usuario = $repoUsuario->findById($row['usuario_id']);
        }

        $token = new Token($row['valor'], $usuario);
        $token->setId($row['id']);

        return $token;
    }
}
