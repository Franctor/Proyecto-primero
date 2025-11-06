<?php
namespace services;
use repositories\Connection;
use repositories\RepoUsuario;
use repositories\RepoEmpresa;
use Exception;
class EmpresaService {
    public function registrarEmpresa($usuario, $empresa) {
        $conn = null;
        $resultado = false;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoUsuario = new RepoUsuario();
            $repoEmpresa = new RepoEmpresa();

            $usuario = $repoUsuario->saveConConexion($usuario, $conn);
            if (!$usuario || !$usuario->getId()) {
                throw new Exception("Error al guardar usuario");
            }

            $empresa->setUsuario($usuario);
            $empresa = $repoEmpresa->saveConConexion($empresa, $conn);
            if (!$empresa || !$empresa->getId()) {
                throw new Exception("Error al guardar empresa");
            }

            $conn->commit();
            $resultado = true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error en registro empresa: " . $e->getMessage());
        }

        return $resultado;
    }   
}