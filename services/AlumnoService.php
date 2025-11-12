<?php
namespace services;
use helpers\Converter;
use helpers\Security;
use models\Usuario;
use repositories\Connection;
use repositories\RepoAlumno;
use repositories\RepoUsuario;
use repositories\RepoToken;
use repositories\RepoSolicitud;
use repositories\RepoCiclo;
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
        $password = isset($data['password']) ? Security::hashPassword(trim($data['password'])) : null;
        $localidad_id = trim($data['localidad']);
        // Incluir los ciclos del usuario
        $ciclos = isset($data['ciclosSeleccionados']) ? $data['ciclosSeleccionados'] : [];
        // === Manejo de archivos ===
        $fotoRuta = null;
        $cvRuta = null;

        // Carpeta base
        $carpetaFotos = __DIR__ . '/../storage/foto_perfil/';
        $carpetaCVs = __DIR__ . '/../storage/cv/';

        
        // Guardar foto si viene
        if (isset($files['foto-perfil']) && $files['foto-perfil']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = $nombre_usuario . '.png';
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

        return $this->registrarAlumno($usuario, $alumno, $ciclos);
    }

    public function registrarAlumno($usuario, $alumno, $ciclos = [])
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

             // Guardar ciclos del alumno
            if (!empty($ciclos)) {
                $repoCiclo = new RepoCiclo();
                foreach ($ciclos as $cicloId) {   
                    if(!$repoCiclo->saveCicloAlumnoConConexion($alumno->getId(), $cicloId, $conn)) {
                        throw new Exception("Error al guardar ciclo-alumno");
                    }
                }
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

            // Eliminar archivos asociados
            $fotoRuta = $alumno->getFoto();
            $cvRuta = $alumno->getCv();
            if ($fotoRuta && file_exists(__DIR__ . '/../' . $fotoRuta)) {
                unlink(__DIR__ . '/../' . $fotoRuta);
            }
            if ($cvRuta && file_exists(__DIR__ . '/../' . $cvRuta)) {
                unlink(__DIR__ . '/../' . $cvRuta);
            }
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al eliminar alumno (transacción): " . $e->getMessage());
        }

        return $ok;
    }

    public function getAlumnos()
    {
        $repoAlumno = new RepoAlumno();
        $alumnos = $repoAlumno->findAll(true);
        if ($alumnos) {
            $converter = new Converter();
            $alumnos = $converter->convertirAlumnosAJson($alumnos);
        }
        return $alumnos;
    }

    public function getAlumno($id)
    {
        $repoAlumno = new RepoAlumno();
        $alumno = $repoAlumno->findById($id, true);
        if ($alumno) {
            $converter = new Converter();
            $alumno = $converter->convertirAlumnoAJson($alumno);
        }
        return $alumno;
    }

    public function updateAlumno($id, $data, $files)
    {
        $resultado = null;

        $repoAlumno = new RepoAlumno();
        $alumno = $repoAlumno->findById($id, true);

        if ($alumno) {
            $usuario = $alumno->getUsuario();
            if ($usuario) {
                // === Datos a actualizar ===
                $nombre = isset($data['nombre']) ? trim($data['nombre']) : $alumno->getNombre();
                $apellido = isset($data['apellido']) ? trim($data['apellido']) : $alumno->getApellido();
                $telefono = isset($data['telefono']) ? trim($data['telefono']) : $alumno->getTelefono();
                $direccion = isset($data['direccion']) ? trim($data['direccion']) : $alumno->getDireccion();
                $nombre_usuario = isset($data['email']) ? trim($data['email']) : $usuario->getNombreUsuario();
                $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null;
                $localidad_id = isset($data['localidad']) ? trim($data['localidad']) : $usuario->getLocalidadId();

                // === Manejo de archivos ===
                $fotoRuta = $alumno->getFoto();
                $cvRuta = $alumno->getCv();

                $carpetaFotos = __DIR__ . '/../storage/foto_perfil/';
                $carpetaCVs = __DIR__ . '/../storage/cv/';

                if (isset($files['foto-perfil']) && $files['foto-perfil']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($files['foto-perfil']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = $nombre_usuario . '.' . $ext;
                    $destino = $carpetaFotos . $nombreArchivo;
                    move_uploaded_file($files['foto-perfil']['tmp_name'], $destino);
                    $fotoRuta = 'storage/foto_perfil/' . $nombreArchivo;
                }

                if (isset($files['cv']) && $files['cv']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($files['cv']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = $nombre_usuario . '.' . strtolower($ext);
                    $destino = $carpetaCVs . $nombreArchivo;
                    move_uploaded_file($files['cv']['tmp_name'], $destino);
                    $cvRuta = 'storage/cv/' . $nombreArchivo;
                }

                // === Actualizar objetos ===
                $alumno->setNombre($nombre);
                $alumno->setApellido($apellido);
                $alumno->setTelefono($telefono);
                $alumno->setDireccion($direccion);
                $alumno->setFoto($fotoRuta);
                $alumno->setCv($cvRuta);

                $usuario->setNombreUsuario($nombre_usuario);
                $usuario->setLocalidadId($localidad_id);
                if ($password) {
                    $usuario->setPassword($password);
                }

                $alumno->setUsuario($usuario);

                // === Guardar cambios ===
                $resultado = $this->actualizarAlumno($usuario, $alumno);
            }
        }

        return $resultado;
    }

    public function actualizarAlumno($usuario, $alumno)
    {
        $conn = null;
        $resultado = null;

        try {
            $conn = Connection::getConnection();
            $conn->beginTransaction();

            $repoUsuario = new RepoUsuario();
            $repoAlumno = new RepoAlumno();

            //Actualizar usuario
            $usuario = $repoUsuario->updateConConexion($usuario, $conn);
            if (!$usuario || !$usuario->getId()) {
                throw new Exception("Error al actualizar usuario");
            }

            //Asignar usuario al alumno y actualizar
            $alumno->setUsuario($usuario);
            $alumno = $repoAlumno->updateConConexion($alumno, $conn);
            if (!$alumno || !$alumno->getId()) {
                throw new Exception("Error al actualizar alumno");
            }

            // Si todo va bien
            $conn->commit();
            $resultado = $alumno;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error en actualización alumno: " . $e->getMessage());
        }

        return $resultado;
    }
}