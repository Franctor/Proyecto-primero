<?php
namespace services;
use PDO;
use Exception;
use repositories\Connection;


class ProvinciaService
{
    public function getAllProvincias()
    {
        $resultado = [];

        try {
            $conn = Connection::getConnection();
            $sql = "SELECT 
                        p.id AS provincia_id,
                        p.nombre_prov,
                        l.id AS localidad_id,
                        l.nombre_loc
                    FROM provincia p
                    LEFT JOIN localidad l ON p.id = l.provincia_id
                    ORDER BY p.id, l.id";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultados) {
                $provincias = [];
                foreach ($resultados as $fila) {
                    $idProvincia = $fila['provincia_id'];

                    if (!isset($provincias[$idProvincia])) {
                        $provincias[$idProvincia] = [
                            'id' => $idProvincia,
                            'nombre_prov' => $fila['nombre_prov'],
                            'localidades' => []
                        ];
                    }

                    if ($fila['localidad_id'] !== null) {
                        $provincias[$idProvincia]['localidades'][] = [
                            'id' => $fila['localidad_id'],
                            'nombre_loc' => $fila['nombre_loc']
                        ];
                    }
                }
                $resultado = array_values($provincias);
            }

        } catch (Exception $e) {
            error_log("Error al obtener provincias con localidades: " . $e->getMessage());
        }

        return $resultado;
    }

    public function getProvinciaById($idProvincia)
    {
        $provincia = null;

        try {
            $conn = Connection::getConnection();

            // Primero verificar si la provincia existe
            $sqlCheck = "SELECT id, nombre_prov FROM provincia WHERE id = :idProvincia";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindValue(':idProvincia', $idProvincia, PDO::PARAM_INT);
            $stmtCheck->execute();

            $provinciaData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($provinciaData) {
                // Si existe, obtener provincia con localidades
                $sql = "SELECT 
                        p.id AS provincia_id,
                        p.nombre_prov,
                        l.id AS localidad_id,
                        l.nombre_loc
                    FROM provincia p
                    LEFT JOIN localidad l ON p.id = l.provincia_id
                    WHERE p.id = :idProvincia
                    ORDER BY l.id";

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':idProvincia', $idProvincia, PDO::PARAM_INT);
                $stmt->execute();

                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $provincia = [
                    'id' => $provinciaData['id'],
                    'nombre_prov' => $provinciaData['nombre_prov'],
                    'localidades' => []
                ];

                foreach ($resultados as $fila) {
                    if ($fila['localidad_id'] !== null) {
                        $provincia['localidades'][] = [
                            'id' => $fila['localidad_id'],
                            'nombre_loc' => $fila['nombre_loc']
                        ];
                    }
                }
            }

        } catch (Exception $e) {
            error_log("Error al obtener provincia por ID: " . $e->getMessage());
        }

        return $provincia;
    }

    public function getByLocalidadId($localidadId)
    {
        $provincia = null;

        try {
            $conn = Connection::getConnection();
            $sql = "SELECT p.id AS provincia_id, p.nombre_prov, l.id AS localidad_id, l.nombre_loc
                FROM provincia p
                JOIN localidad l ON p.id = l.provincia_id
                WHERE l.id = :localidadId";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':localidadId', $localidadId, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $provincia = [
                    'id' => $row['provincia_id'],
                    'nombre_prov' => $row['nombre_prov'],
                    'localidad' => [
                        'id' => $row['localidad_id'],
                        'nombre_loc' => $row['nombre_loc']
                    ]
                ];
            }
        } catch (Exception $e) {
            error_log("Error al obtener provincia por ID de localidad: " . $e->getMessage());
        }

        return $provincia;
    }
}