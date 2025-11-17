<?php
namespace services;
use helpers\Security;
use repositories\Connection;
use repositories\RepoUsuario;
use repositories\RepoEmpresa;
use repositories\RepoToken;
use repositories\RepoOferta;
use models\Usuario;
use models\Empresa;
use Exception;
class EmpresaService
{
    public function registrarEmpresa($inputs, $files = [])
    {
        // === Limpieza de datos ===
        $nombre_usuario = trim($inputs['email']);
        $password = isset($inputs['password']) ? Security::hashPassword(trim($inputs['password'])) : null;
        $localidad_id = trim($inputs['localidad']);
        $nombre_empresa = ucfirst(trim($inputs['nombre']));
        $telefono = trim($inputs['telefono']);
        $direccion = trim($inputs['direccion']);
        $nombre_persona = ucfirst(trim($inputs['nombre_persona']));
        $telefono_persona = trim($inputs['telefono_persona']);
        $descripcion = isset($inputs['descripcion']) ? trim($inputs['descripcion']) : 'undefined';

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

    public function obtenerTodasEmpresas()
    {
        $repoEmpresa = new RepoEmpresa();
        return $repoEmpresa->findAll();
    }

    public function getEmpresaById($idEmpresa)
    {
        $repoEmpresa = new RepoEmpresa();
        return $repoEmpresa->findById($idEmpresa, true);
    }

    public function eliminarEmpresa($empresaId)
    {
        $ok = false;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoEmpresa = new RepoEmpresa();
            $repoUsuario = new RepoUsuario();
            $repoToken = new RepoToken();
            $repoOferta = new RepoOferta();

            // Obtener usuario asociado al alumno
            $empresa = $repoEmpresa->findById($empresaId, true);
            if (!$empresa) {
                throw new Exception("Empresa no encontrada con ID $empresaId");
            }

            $usuarioId = $empresa->getUsuario()->getId();

            // Eliminar en orden lógico
            $repoToken->deleteByUsuarioId($usuarioId, $conn);
            $repoOferta->deleteByEmpresaId($empresaId, $conn);
            $repoEmpresa->delete($empresaId, $conn);
            $repoUsuario->delete($usuarioId, $conn);

            $conn->commit();
            $ok = true;

            // Eliminar archivos asociados
            $fotoRuta = $empresa->getFoto();
            if ($fotoRuta && file_exists(__DIR__ . '/../' . $fotoRuta)) {
                unlink(__DIR__ . '/../' . $fotoRuta);
            }
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al eliminar empresa (transacción): " . $e->getMessage());
        }

        return $ok;
    }

    public function editarEmpresa($empresaId, $inputs)
    {
        $resultado = null;

        $repoEmpresa = new RepoEmpresa();
        $empresa = $repoEmpresa->findById($empresaId, true);

        if ($empresa) {
            $usuario = $empresa->getUsuario();
            if ($usuario) {
                // === Datos a actualizar ===
                $nombre_usuario = isset($inputs["email"]) ? trim($inputs["email"]) : $usuario->getNombreUsuario();
                $nombre_empresa = isset($inputs['nombre']) ? ucfirst(trim($inputs['nombre'])) : $empresa->getNombre();
                $telefono_empresa = isset($inputs['telefono']) ? trim($inputs['telefono']) : $empresa->getTelefono();
                $direccion = isset($inputs['direccion']) ? trim($inputs['direccion']) : $empresa->getDireccion();
                $nombre_persona = isset($inputs['nombre_persona']) ? ucfirst(trim($inputs['nombre_persona'])) : $empresa->getNombrePersona();
                $telefono_persona = isset($inputs['telefono_persona']) ? trim($inputs['telefono_persona']) : $empresa->getTelefonoPersona();
                $verificada = isset($inputs['verificada']) ? (int)$inputs['verificada'] : $empresa->getVerificada();


                // === Actualizar objetos ===
                $empresa->setNombre($nombre_empresa);
                $empresa->setTelefono($telefono_empresa);
                $empresa->setDireccion($direccion);
                $empresa->setNombrePersona($nombre_persona);
                $empresa->setTelefonoPersona($telefono_persona);
                $empresa->setVerificada($verificada);

                $usuario->setNombreUsuario($nombre_usuario);

                $empresa->setUsuario($usuario);

                // === Guardar cambios ===
                $resultado = $this->actualizarEmpresa($usuario, $empresa);
            }
        }
        return $resultado;
    }

     public function actualizarEmpresa($usuario, $empresa)
    {
        $conn = null;
        $resultado = null;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoUsuario = new RepoUsuario();
            $repoEmpresa = new RepoEmpresa();

            //Actualizar usuario
            $usuario = $repoUsuario->updateConConexion($usuario, $conn);
            if (!$usuario || !$usuario->getId()) {
                throw new Exception("Error al actualizar usuario");
            }

            //Asignar usuario al alumno y actualizar
            $empresa->setUsuario($usuario);
            $empresa = $repoEmpresa->update($empresa, $conn);
            if (!$empresa || !$empresa->getId()) {
                throw new Exception("Error al actualizar empresa");
            } 

            // Si todo va bien
            $conn->commit();
            $resultado = $empresa;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error en actualización empresa: " . $e->getMessage());
        }

        return $resultado;
    }
}