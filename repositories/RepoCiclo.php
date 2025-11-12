<?php
namespace repositories;

use PDO;
use Exception;
use models\Ciclo;

class RepoCiclo
{
    public function save($ciclo)
    {
        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                INSERT INTO ciclo (nombre, nivel, familia_id)
                VALUES (:nombre, :nivel, :familia_id)
            ");

            $stmt->bindValue(':nombre', $ciclo->getNombre());
            $stmt->bindValue(':nivel', $ciclo->getNivel());
            $stmt->bindValue(':familia_id', $ciclo->getFamilia() ? $ciclo->getFamilia()->getId() : null, PDO::PARAM_INT);

            $stmt->execute();
            $ciclo->setId($conn->lastInsertId());
        } catch (Exception $e) {
            error_log("Error al guardar ciclo: " . $e->getMessage());
            $ciclo = null;
        }

        return $ciclo;
    }

    public function saveCicloAlumnoConConexion($alumnoId, $cicloId, $conn)
    {
        try {
            $stmt = $conn->prepare("
                INSERT INTO alumnos_ciclos (alumno_id, ciclo_id)
                VALUES (:alumno_id, :ciclo_id)
            ");

            $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $stmt->bindValue(':ciclo_id', $cicloId, PDO::PARAM_INT);

            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error al guardar ciclo-alumno: " . $e->getMessage());
            return false;
        }
    }
    public function findById($id, $loadFamilia = false, $loadAlumnos = false, $loadOfertas = false)
    {
        $ciclo = null;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM ciclo WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $repoFamilia = $loadFamilia ? new RepoFamilia() : null;
                $repoAlumno = $loadAlumnos ? new RepoAlumno() : null;
                $repoOferta = $loadOfertas ? new RepoOferta() : null;

                $ciclo = $this->mapRowToCiclo($row, $repoFamilia, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar ciclo por ID: " . $e->getMessage());
        }

        return $ciclo;
    }

    public function findAll($loadFamilia = false, $loadAlumnos = false, $loadOfertas = false)
    {
        $ciclos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->query("SELECT * FROM ciclo ORDER BY id DESC");

            $repoFamilia = $loadFamilia ? new RepoFamilia() : null;
            $repoAlumno = $loadAlumnos ? new RepoAlumno() : null;
            $repoOferta = $loadOfertas ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciclos[] = $this->mapRowToCiclo($row, $repoFamilia, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al obtener todos los ciclos: " . $e->getMessage());
        }

        return $ciclos;
    }

    public function findByAlumnoId($alumnoId, $loadFamilia = false, $loadAlumnos = false, $loadOfertas = false)
    {
        $ciclos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
            SELECT c.*
            FROM ciclo c
            INNER JOIN alumnos_ciclos ac ON c.id = ac.ciclo_id
            WHERE ac.alumno_id = :alumnoId
        ");
            $stmt->bindValue(':alumnoId', $alumnoId, PDO::PARAM_INT);
            $stmt->execute();

            $repoFamilia = $loadFamilia ? new RepoFamilia() : null;
            $repoAlumno = $loadAlumnos ? new RepoAlumno() : null;
            $repoOferta = $loadOfertas ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciclos[] = $this->mapRowToCiclo($row, $repoFamilia, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al buscar ciclos por alumno ID: " . $e->getMessage());
        }

        return $ciclos;
    }
    public function findByOfertaId($ofertaId, $loadFamilia = false, $loadAlumnos = false, $loadOfertas = false)
    {
        $ciclos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
            SELECT c.*
            FROM ciclo c
            INNER JOIN ofertas_ciclos oc ON c.id = oc.ciclo_id
            WHERE oc.oferta_id = :ofertaId
        ");
            $stmt->bindValue(':ofertaId', $ofertaId, PDO::PARAM_INT);
            $stmt->execute();

            $repoFamilia = $loadFamilia ? new RepoFamilia() : null;
            $repoAlumno = $loadAlumnos ? new RepoAlumno() : null;
            $repoOferta = $loadOfertas ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciclo = $this->mapRowToCiclo($row, $repoFamilia, $repoAlumno, $repoOferta);

                $ciclos[] = $ciclo;
            }
        } catch (Exception $e) {
            error_log("Error al buscar ciclos por oferta ID: " . $e->getMessage());
        }

        return $ciclos;
    }

    public function findByFamiliaId($familiaId, $loadFamilia = false, $loadAlumnos = false, $loadOfertas = false)
    {
        $ciclos = [];

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT * FROM ciclo WHERE familia_id = :familia_id ORDER BY id DESC");
            $stmt->bindValue(':familia_id', $familiaId, PDO::PARAM_INT);
            $stmt->execute();

            $repoFamilia = $loadFamilia ? new RepoFamilia() : null;
            $repoAlumno = $loadAlumnos ? new RepoAlumno() : null;
            $repoOferta = $loadOfertas ? new RepoOferta() : null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ciclos[] = $this->mapRowToCiclo($row, $repoFamilia, $repoAlumno, $repoOferta);
            }
        } catch (Exception $e) {
            error_log("Error al obtener ciclos por familia ID: " . $e->getMessage());
        }

        return $ciclos;
    }
    public function update($ciclo)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("
                UPDATE ciclo
                SET nombre = :nombre,
                    nivel = :nivel,
                    familia_id = :familia_id
                WHERE id = :id
            ");

            $stmt->bindValue(':nombre', $ciclo->getNombre());
            $stmt->bindValue(':nivel', $ciclo->getNivel());
            $stmt->bindValue(':familia_id', $ciclo->getFamilia() ? $ciclo->getFamilia()->getId() : null, PDO::PARAM_INT);
            $stmt->bindValue(':id', $ciclo->getId(), PDO::PARAM_INT);

            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar ciclo: " . $e->getMessage());
        }

        return $result;
    }

    public function delete($id)
    {
        $result = false;

        try {
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("DELETE FROM ciclo WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar ciclo: " . $e->getMessage());
        }

        return $result;
    }

    private function mapRowToCiclo($row, $repoFamilia = null, $repoAlumnos = null, $repoOfertas = null)
    {
        $familia = null;
        $alumnos = [];
        $ofertas = [];

        if ($repoFamilia && $row['familia_id']) {
            $familia = $repoFamilia->findById($row['familia_id']);
        }

        if ($repoAlumnos) {
            $alumnos = $repoAlumnos->findByCicloId($row['id']);
        }

        if ($repoOfertas) {
            $ofertas = $repoOfertas->findByCicloId($row['id']);
        }

        $ciclo = new Ciclo($row['nombre'], $row['nivel'], [], [], $familia);
        $ciclo->setId($row['id']);

        if ($repoAlumnos) {
            $ciclo->setAlumnos($alumnos);
        }

        if ($repoOfertas) {
            $ciclo->setOfertas($ofertas);
        }

        return $ciclo;
    }
}
