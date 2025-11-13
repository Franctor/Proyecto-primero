<?php
namespace services;
use helpers\Security;
use repositories\Connection;
use repositories\RepoUsuario;
use repositories\RepoEmpresa;
use models\Usuario;
use models\Empresa;
use Exception;
class EmpresaService
{
    public function registrarEmpresa($inputs, $files)
    {
        // === Limpieza de datos ===
        $nombre_usuario = trim($inputs['email']);
        $password = isset($inputs['password']) ? Security::hashPassword(trim($inputs['password'])) : null;
        $localidad_id = trim($inputs['localidad']);

        $nombre_empresa = trim($inputs['nombre_empresa']);
        $telefono = trim($inputs['telefono']);
        $direccion = trim($inputs['direccion']);
        $nombre_persona = trim($inputs['nombre_persona']);
        $telefono_persona = trim($inputs['telefono_persona']);
        $descripcion = trim($inputs['descripcion']);

        // === Manejo de archivos ===
        $logoRuta = null;
        $carpetaLogos = __DIR__ . '/../storage/foto_perfil/';

        // Guardar logo si viene
        if (isset($files['logo']) && $files['logo']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($files['logo']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $nombre_usuario . '.' . strtolower($extension);
            $destino = $carpetaLogos . $nombreArchivo;

            move_uploaded_file($files['logo']['tmp_name'], $destino);
            $logoRuta = 'storage/foto_perfil/' . $nombreArchivo; // ruta relativa
        } else {
            $logoRuta = 'storage/foto_perfil/default.png'; // ruta por defecto
        }

        // === Crear objetos ===
        $usuario = new Usuario(
            $nombre_usuario,
            $password,
            3,
            $localidad_id
        );

        $empresa = new Empresa(
            $nombre_empresa,
            $telefono,
            $direccion,
            $nombre_persona,
            $telefono_persona,
            $logoRuta,
            0,
            $descripcion
        );

        // === Registrar en base de datos ===
        return $this->guardarEmpresa($usuario, $empresa);
    }


    private function guardarEmpresa($usuario, $empresa)
    {
        $conn = null;
        $resultado = false;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoUsuario = new RepoUsuario();
            $repoEmpresa = new RepoEmpresa();

            // === Guardar usuario ===
            $usuario = $repoUsuario->saveConConexion($usuario, $conn);
            if (!$usuario || !$usuario->getId()) {
                throw new Exception("Error al guardar usuario");
            }

            // === Asignar usuario a la empresa y guardar ===
            $empresa->setUsuario($usuario);
            $empresa = $repoEmpresa->saveConConexion($empresa, $conn);
            if (!$empresa || !$empresa->getId()) {
                throw new Exception("Error al guardar empresa");
            }

            // === Si todo va bien ===
            $conn->commit();
            $resultado = true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error en registro empresa: " . $e->getMessage());
        }

        return $resultado ? $empresa : null;
    }

}