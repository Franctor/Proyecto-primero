<?php
namespace services;
use repositories\Connection;
use repositories\RepoAlumno;
use repositories\RepoUsuario;
use repositories\RepoToken;
use repositories\RepoSolicitud;
use Exception;
class AlumnoService
{
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

        return $resultado;
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