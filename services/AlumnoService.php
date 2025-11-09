<?php
namespace services;
use models\Usuario;
use repositories\Connection;
use repositories\RepoAlumno;
use repositories\RepoUsuario;
use repositories\RepoToken;
use repositories\RepoSolicitud;
use models\Alumno;
use Exception;
class AlumnoService
{
    public function createAlumno($data, $files)
    {
        $nombre = trim($data['nombre']);
        $apellido = trim($data['apellido']);
        $telefono = trim($data['telefono']);
        $direccion = trim($data['direccion']);
        $nombre_usuario = trim($data['email']);
        $password = isset($data['password']) ? trim($data['password']) : null;
        $localidad_id = trim($data['localidad']);

        // === Manejo de archivos ===
        $fotoRuta = null;
        $cvRuta = null;

        // Carpeta base
        $carpetaFotos = __DIR__ . '/../storage/foto_perfil/';
        $carpetaCVs = __DIR__ . '/../storage/cv/';

        // Guardar foto si viene
        if (isset($files['foto-perfil']) && $files['foto-perfil']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = $nombre_usuario;
            $destino = $carpetaFotos . $nombreArchivo;
            move_uploaded_file($files['foto-perfil']['tmp_name'], $destino);
            $fotoRuta = 'storage/foto_perfil/' . $nombreArchivo; // ruta relativa
        }

        // Guardar CV si viene
        if (isset($files['cv']) && $files['cv']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($files['cv']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $nombre_usuario . '.' . strtolower($extension);
            $destino = $carpetaCVs . $nombreArchivo;
            move_uploaded_file($files['cv']['tmp_name'], $destino);
            $cvRuta = 'storage/cv/' . $nombreArchivo; // ruta relativa
        }

        // Crear objetos
        $alumno = new Alumno(
            $nombre,
            $apellido,
            $telefono,
            $direccion,
            $fotoRuta,
            $cvRuta,
            0
        );

        $usuario = new Usuario(
            $nombre_usuario,
            $password,
            2,
            $localidad_id
        );

        return $this->registrarAlumno($usuario, $alumno);
    }

    public function registrarAlumno($usuario, $alumno)
    {
        $conn = null;
        $resultado = false;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoUsuario = new RepoUsuario();
            $repoAlumno = new RepoAlumno();

            //Guardar usuario
            $usuario = $repoUsuario->saveConConexion($usuario, $conn);
            if (!$usuario || !$usuario->getId()) {
                throw new Exception("Error al guardar usuario");
            }

            //Asignar usuario al alumno y guardar
            $alumno->setUsuario($usuario);
            $alumno = $repoAlumno->saveConConexion($alumno, $conn);
            if (!$alumno || !$alumno->getId()) {
                throw new Exception("Error al guardar alumno");
            }

            // Si todo va bien
            $conn->commit();
            $resultado = true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error en registro alumno: " . $e->getMessage());
        }

        return $resultado ? $alumno : null;
    }
    public function deleteAlumno($alumnoId)
    {
        $ok = false;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoAlumno = new RepoAlumno();
            $repoUsuario = new RepoUsuario();
            $repoToken = new RepoToken();
            $repoSolicitud = new RepoSolicitud();

            // Obtener usuario asociado al alumno
            $alumno = $repoAlumno->findById($alumnoId, true);
            if (!$alumno) {
                throw new Exception("Alumno no encontrado con ID $alumnoId");
            }

            $usuarioId = $alumno->getUsuario()->getId();

            // Eliminar en orden lógico
            $repoToken->deleteByUsuarioId($usuarioId);
            $repoSolicitud->deleteByAlumnoId($alumnoId);
            $repoAlumno->deleteCiclosByAlumnoId($alumnoId);
            $repoAlumno->delete($alumnoId);
            $repoUsuario->delete($usuarioId);

            $conn->commit();
            $ok = true;

        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al eliminar alumno (transacción): " . $e->getMessage());
        }

        return $ok;
    }
}