<?php
class RepoEmpresa {

    public function save(Empresa $empresa) {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO empresas (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, verificada, descripcion)
                VALUES (:nombre, :telefono, :direccion, :nombre_persona, :telefono_persona, :logo, :verificada, :descripcion)
            ");

            $stmt->bindValue(':nombre', $empresa->getNombre());
            $stmt->bindValue(':telefono', $empresa->getTelefono());
            $stmt->bindValue(':direccion', $empresa->getDireccion());
            $stmt->bindValue(':nombre_persona', $empresa->getNombrePersona());
            $stmt->bindValue(':telefono_persona', $empresa->getTelefonoPersona());
            $stmt->bindValue(':logo', $empresa->getLogo());
            $stmt->bindValue(':verificada', $empresa->getVerificada(), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $empresa->getDescripcion());

            $stmt->execute();
            $empresa->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar empresa: " . $e->getMessage());
            $empresa = null;
        }

        return $empresa;
    }

    public function findById($id) {
        $empresa = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM empresas WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $empresa = new Empresa(
                    $row['nombre'],
                    $row['telefono'],
                    $row['direccion'],
                    $row['nombre_persona'],
                    $row['telefono_persona'],
                    $row['logo'],
                    $row['descripcion']
                );
                $empresa->setId($row['id']);
                $empresa->setVerificada($row['verificada']);
            }
        } catch (Exception $e) {
            error_log("Error al buscar empresa por ID: " . $e->getMessage());
        }

        return $empresa;
    }

    public function findAll() {
        $empresas = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM empresas ORDER BY id DESC");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $empresa = new Empresa(
                    $row['nombre'],
                    $row['telefono'],
                    $row['direccion'],
                    $row['nombre_persona'],
                    $row['telefono_persona'],
                    $row['logo'],
                    $row['descripcion']
                );
                $empresa->setId($row['id']);
                $empresa->setVerificada($row['verificada']);
                $empresas[] = $empresa;
            }
        } catch (Exception $e) {
            error_log("Error al obtener todas las empresas: " . $e->getMessage());
        }

        return $empresas;
    }

    public function update(Empresa $empresa) {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE empresas
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
            $stmt->bindValue(':logo', $empresa->getLogo());
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

    public function delete($id) {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM empresas WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar empresa: " . $e->getMessage());
        }

        return $result;
    }
}
