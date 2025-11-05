<?php
namespace repositories;

use PDO;
use Exception;
use models\Familia;

class RepoFamilia
{
    public function save($familia)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO familia (nombre)
                VALUES (:nombre)
            ");

            $stmt->bindValue(':nombre', $familia->getNombre());
            $stmt->execute();

            $familia->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar familia: " . $e->getMessage());
            $familia = null;
        }

        return $familia;
    }

    public function findById($id, $loadCiclos = false)
    {
        $familia = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM familia WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            if ($row) {
                $familia = $this->mapRowToFamilia($row, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al buscar familia por ID: " . $e->getMessage());
        }

        return $familia;
    }

    public function findAll($loadCiclos = false)
    {
        $familias = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM familia ORDER BY id DESC");

            $repoCiclo = $loadCiclos ? new RepoCiclo() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $familias[] = $this->mapRowToFamilia($row, $repoCiclo);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todas las familias: " . $e->getMessage());
        }

        return $familias;
    }

    public function update($familia)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE familia
                SET nombre = :nombre
                WHERE id = :id
            ");

            $stmt->bindValue(':nombre', $familia->getNombre());
            $stmt->bindValue(':id', $familia->getId(), PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar familia: " . $e->getMessage());
            $familia = null;
        }

        return $familia;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM familia WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar familia: " . $e->getMessage());
        }

        return $result;
    }

    private function mapRowToFamilia($row, $repoCiclo = null)
    {
        $familia = new Familia($row['nombre']);
        $familia->setId($row['id']);

        if ($repoCiclo) {
            $familia->setCiclos($repoCiclo->findByFamiliaId($familia->getId()));
        }

        return $familia;
    }
}
?>
