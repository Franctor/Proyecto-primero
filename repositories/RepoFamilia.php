<?php
namespace repositories;
class RepoFamilia {
    public function save(Familia $familia) {
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

    public function findById($id) {
        $familia = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM familia WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $familia = new Familia($row['nombre']);
                $familia->setId($row['id']);
            }
        } catch (Exception $e) {
            error_log("Error al buscar familia por ID: " . $e->getMessage());
        }

        return $familia;
    }

    public function findAll() {
        $familias = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM familia ORDER BY id DESC");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $familia = new Familia($row['nombre']);
                $familia->setId($row['id']);
                $familias[] = $familia;
            }
        } catch (Exception $e) {
            error_log("Error al obtener todas las familias: " . $e->getMessage());
        }

        return $familias;
    }

    public function update(Familia $familia) {
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

    public function delete($id) {
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
}
?>
