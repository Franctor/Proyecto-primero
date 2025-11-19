<?php
namespace helpers;

use DateTime;
use repositories\RepoUsuario;
use repositories\RepoAlumno;

class Validator
{
    // ===================== Validaciones simples =====================

    private function validarNombre($nombre)
    {
        $errores = [];
        $nombre = trim($nombre);

        if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 45) {
            $errores[] = "El nombre debe tener entre 2 y 45 caracteres.";
        } elseif (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'.-]+$/", $nombre)) {
            $errores[] = "El nombre contiene caracteres inválidos.";
        }

        return $errores;
    }

    private function validarDireccion($direccion)
    {
        $errores = [];
        $direccion = trim($direccion);

        if (empty($direccion) || strlen($direccion) < 5 || strlen($direccion) > 100) {
            $errores[] = "La dirección debe tener entre 5 y 100 caracteres.";
        } elseif (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s.,\-\/ºª]+$/", $direccion)) {
            $errores[] = "La dirección contiene caracteres inválidos.";
        }

        return $errores;
    }

    private function validarTelefono($telefono)
    {
        $errores = [];
        $telefono = trim($telefono);

        if (empty($telefono)) {
            $errores[] = "El teléfono no puede estar vacío.";
        } elseif (!preg_match("/^(?:\+34)?[67]\d{8}$/", $telefono)) {
            $errores[] = "El formato del teléfono es inválido.";
        }

        return $errores;
    }

    public function validarCorreoElectronico($email)
    {
        $errores = [];
        $email = trim($email);

        if (empty($email) || strlen($email) < 5 || strlen($email) > 100) {
            $errores[] = "El correo electrónico debe tener entre 5 y 100 caracteres.";
        } elseif (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
            $errores[] = "El formato del correo electrónico es inválido.";
        }

        return $errores;
    }

    private function validarPassword($password)
    {
        $errores = [];

        if (!isset($password) || $password === '') {
            $errores[] = "La contraseña no puede estar vacía.";
        } else {
            if (strlen($password) < 8) {
                $errores[] = "Debe tener al menos 8 caracteres";
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errores[] = "Debe contener al menos una letra mayúscula";
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errores[] = "Debe contener al menos una letra minúscula";
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errores[] = "Debe contener al menos un número";
            }
            if (!preg_match('/[!@#$%^&*()\-_+=]/', $password)) {
                $errores[] = "Debe contener al menos un carácter especial";
            }
            if (preg_match('/\s/', $password)) {
                $errores[] = "No puede contener espacios";
            }
        }

        return $errores;
    }

    private function validarNumeroEntero($numero)
    {
        $errores = [];
        $numero = trim($numero);

        if (filter_var($numero, FILTER_VALIDATE_INT) === false) {
            $errores[] = "El valor no es un número entero.";
        }

        return $errores;
    }

    private function validarDescripcion($descripcion, $maxLength = 500)
    {
        $errores = [];
        $descripcion = trim($descripcion);

        if (empty($descripcion)) {
            $errores[] = "La descripción no puede estar vacía.";
        } elseif (strlen($descripcion) > $maxLength) {
            $errores[] = "La descripción no puede superar $maxLength caracteres.";
        }

        return $errores;
    }

    private function validarFoto($foto)
    {
        $errores = [];
        $tiposFoto = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 1 * 1024 * 1024;

        if (!isset($foto) || $foto['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "No se subió ninguna foto de perfil.";
        } elseif (!in_array($foto['type'], $tiposFoto)) {
            $errores[] = "La foto debe ser JPG, PNG o WEBP.";
        } elseif ($foto['size'] > $maxSize) {
            $errores[] = "La foto no puede superar 1MB.";
        } elseif (!is_uploaded_file($foto['tmp_name'])) {
            $errores[] = "Error al subir la foto.";
        }

        return $errores;
    }

    private function validarCV($cv)
    {
        $errores = [];
        $tiposCV = ['application/pdf', 'application/x-pdf'];
        $maxSize = 5 * 1024 * 1024;

        if (!isset($cv) || $cv['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "No se subió ningún CV.";
        } elseif (!in_array($cv['type'], $tiposCV)) {
            $errores[] = "El CV debe ser PDF.";
        } elseif ($cv['size'] > $maxSize) {
            $errores[] = "El CV no puede superar 5MB.";
        } elseif (!is_uploaded_file($cv['tmp_name'])) {
            $errores[] = "Error al subir el CV.";
        }

        return $errores;
    }

    private function validarArrayCiclos($ciclos)
    {
        $errores = [];
        $numerosVistos = [];

        if (!is_array($ciclos)) {
            $errores[] = "Los ciclos deben enviarse en un array.";
        } else {
            foreach ($ciclos as $index => $ciclo) {
                if (!filter_var($ciclo, FILTER_VALIDATE_INT) && $ciclo !== 0 && $ciclo !== '0') {
                    $errores[] = "El ciclo en la posición $index no es un número entero.";
                    break; // solo un error por campo
                } elseif (in_array($ciclo, $numerosVistos)) {
                    $errores[] = "El ciclo '$ciclo' está repetido.";
                    break;
                } else {
                    $numerosVistos[] = $ciclo;
                }
            }
        }

        return $errores;
    }

    private function validarLocalidad($localidadId)
    {
        $errores = [];
        $localidadId = trim($localidadId);

        if (filter_var($localidadId, FILTER_VALIDATE_INT) === false) {
            $errores[] = "Localidad no válida.";
        }

        return $errores;
    }

    private function validarProvincia($provinciaId)
    {
        $errores = [];
        $provinciaId = trim($provinciaId);

        if (filter_var($provinciaId, FILTER_VALIDATE_INT) === false) {
            $errores[] = "Provincia no válida.";
        }

        return $errores;
    }

    // ===================== Base de datos =====================

    public function correoExiste($email)
    {
        $repoUsuario = new RepoUsuario();
        $existe = false;
        if ($repoUsuario->findByNombreUsuario($email) !== null) {
            $existe = true;
        }
        return $existe;
    }

    public function telefonoExiste($telefono)
    {
        $repoAlumno = new RepoAlumno();
        $existe = false;
        if ($repoAlumno->findByTelefono($telefono) !== null) {
            $existe = true;
        }
        return $existe;
    }

    // ===================== Validaciones compuestas =====================

    public function validarAlumno($input, $files, $archivosObligatorios = true)
    {
        $errores = [];

        // Validar existencia en la base de datos
        if ($this->telefonoExiste($input['telefono'] ?? '') || $this->correoExiste($input['email'] ?? '')) {
            $errores[] = "El correo electrónico y/o teléfono ya está registrado.";
        }

        // Validar campos
        $erroresNombre = $this->validarNombre($input['nombre'] ?? '');
        if (!empty($erroresNombre)) {
            $errores = array_merge($errores, $erroresNombre);
        }

        $erroresApellido = $this->validarNombre($input['apellido'] ?? '');
        if (!empty($erroresApellido)) {
            $errores = array_merge($errores, $erroresApellido);
        }

        $erroresTelefono = $this->validarTelefono($input['telefono'] ?? '');
        if (!empty($erroresTelefono)) {
            $errores = array_merge($errores, $erroresTelefono);
        }

        if ($archivosObligatorios) {
            $erroresDireccion = $this->validarDireccion($input['direccion'] ?? '');
            if (!empty($erroresDireccion)) {
                $errores = array_merge($errores, $erroresDireccion);
            }
        }

        $erroresEmail = $this->validarCorreoElectronico($input['email'] ?? '');
        if (!empty($erroresEmail)) {
            $errores = array_merge($errores, $erroresEmail);
        }

        if (isset($input['password'])) {
            $erroresPassword = $this->validarPassword($input['password']);
            if (!empty($erroresPassword)) {
                $errores = array_merge($errores, $erroresPassword);
            }
        }

        $erroresLocalidad = $this->validarNumeroEntero($input['localidad'] ?? '');
        if (!empty($erroresLocalidad)) {
            $errores = array_merge($errores, $erroresLocalidad);
        }

        // Validación de archivos según contexto
        if ($archivosObligatorios) {
            $erroresFoto = $this->validarFoto($files['foto-perfil'] ?? null);
            $erroresCV = $this->validarCV($files['cv'] ?? null);
            $errores = array_merge($errores, $erroresFoto, $erroresCV);
        } else {
            // archivos opcionales: solo se validan si vienen
            if (!empty($files['foto-perfil']['tmp_name'])) {
                $erroresFoto = $this->validarFoto($files['foto-perfil']);
                $errores = array_merge($errores, $erroresFoto);
            }
            if (!empty($files['cv']['tmp_name'])) {
                $erroresCV = $this->validarCV($files['cv']);
                $errores = array_merge($errores, $erroresCV);
            }
        }

        return empty($errores);
    }


    public function validarAlumnoEditar($input, $files)
    {
        $errores = [];

        $erroresNombre = $this->validarNombre($input['nombre'] ?? '');
        if (!empty($erroresNombre)) {
            $errores = array_merge($errores, $erroresNombre);
        }

        $erroresApellido = $this->validarNombre($input['apellido'] ?? '');
        if (!empty($erroresApellido)) {
            $errores = array_merge($errores, $erroresApellido);
        }

        $erroresTelefono = $this->validarTelefono($input['telefono'] ?? '');
        if (!empty($erroresTelefono)) {
            $errores = array_merge($errores, $erroresTelefono);
        }

        $erroresDireccion = $this->validarDireccion($input['direccion'] ?? '');
        if (!empty($erroresDireccion)) {
            $errores = array_merge($errores, $erroresDireccion);
        }

        $erroresEmail = $this->validarCorreoElectronico($input['email'] ?? '');
        if (!empty($erroresEmail)) {
            $errores = array_merge($errores, $erroresEmail);
        }

        $erroresLocalidad = $this->validarNumeroEntero($input['localidad'] ?? '');
        if (!empty($erroresLocalidad)) {
            $errores = array_merge($errores, $erroresLocalidad);
        }

        if (isset($files['foto-perfil']) && $files['foto-perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $erroresFoto = $this->validarFoto($files['foto-perfil']);
            if (!empty($erroresFoto)) {
                $errores = array_merge($errores, $erroresFoto);
            }
        }

        if (isset($files['cv']) && $files['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
            $erroresCV = $this->validarCV($files['cv']);
            if (!empty($erroresCV)) {
                $errores = array_merge($errores, $erroresCV);
            }
        }

        return empty($errores);
    }


    public function validarFormularioRegistroEmpresa($input, $files)
    {
        $errores = [];


        // Correo
        $erroresEmail = $this->validarCorreoElectronico($input['email'] ?? '');
        if (!empty($erroresEmail)) {
            $errores['email'] = $erroresEmail;
        }

        if ($this->correoExiste($input['email'] ?? '') || $this->telefonoExiste($input['telefono'] ?? '')) {
            $errores['repetido'][] = "El correo electrónico y/o teléfono ya está registrado.";
        }

        // Contraseña
        $erroresPassword = $this->validarPassword($input['password'] ?? '');
        if (!empty($erroresPassword)) {
            $errores['password'] = $erroresPassword;
        }

        // Nombre empresa
        $erroresNombreEmpresa = $this->validarNombre($input['nombre'] ?? '');
        if (!empty($erroresNombreEmpresa)) {
            $errores['nombre'] = $erroresNombreEmpresa;
        }

        // Teléfono
        $erroresTelefono = $this->validarTelefono($input['telefono'] ?? '');
        if (!empty($erroresTelefono)) {
            $errores['telefono'] = $erroresTelefono;
        }

        // Dirección
        $erroresDireccion = $this->validarDireccion($input['direccion'] ?? '');
        if (!empty($erroresDireccion)) {
            $errores['direccion'] = $erroresDireccion;
        }

        // Persona de contacto
        $erroresNombrePersona = $this->validarNombre($input['nombre_persona'] ?? '');
        if (!empty($erroresNombrePersona)) {
            $errores['nombre_persona'] = $erroresNombrePersona;
        }

        $erroresTelefonoPersona = $this->validarTelefono($input['telefono_persona'] ?? '');
        if (!empty($erroresTelefonoPersona)) {
            $errores['telefono_persona'] = $erroresTelefonoPersona;
        }

        // Logo
        if (isset($files['logo']) && $files['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $erroresLogo = $this->validarFoto($files['logo']);
            if (!empty($erroresLogo)) {
                $errores['logo'] = $erroresLogo;
            }
        }

        // Descripción
        $erroresDescripcion = $this->validarDescripcion($input['descripcion'] ?? '');
        if (!empty($erroresDescripcion)) {
            $errores['descripcion'] = $erroresDescripcion;
        }


        // Provincia y localidad
        $erroresProvincia = $this->validarProvincia($input['provincia'] ?? '');
        if (!empty($erroresProvincia)) {
            $errores['provincia'] = $erroresProvincia;
        }

        $erroresLocalidad = $this->validarLocalidad($input['localidad'] ?? '');
        if (!empty($erroresLocalidad)) {
            $errores['localidad'] = $erroresLocalidad;
        }

        $erroresLogo = $this->validarFoto($files['logo'] ?? null);
        if (!empty($erroresLogo)) {
            $errores['logo'] = $erroresLogo;
        }

        $erroresDescripcion = $this->validarDescripcion($input['descripcion'] ?? '');
        if (!empty($erroresDescripcion)) {
            $errores['descripcion'] = $erroresDescripcion;
        }

        return $errores;
    }

    public function validarEmpresaAdmin($data)
    {
        $errores = [];

        // Email
        $erroresEmail = $this->validarCorreoElectronico($data['email'] ?? '');
        if (!empty($erroresEmail)) {
            $errores['email'] = $erroresEmail;
        }

        // Nombre empresa
        $erroresNombre = $this->validarNombre($data['nombre'] ?? '');
        if (!empty($erroresNombre)) {
            $errores['nombre'] = $erroresNombre;
        }

        // Teléfono empresa
        $erroresTelefono = $this->validarTelefono($data['telefono'] ?? '');
        if (!empty($erroresTelefono)) {
            $errores['telefono'] = $erroresTelefono;
        }

        // Dirección
        $erroresDireccion = $this->validarDireccion($data['direccion'] ?? '');
        if (!empty($erroresDireccion)) {
            $errores['direccion'] = $erroresDireccion;
        }

        // Provincia
        $erroresProvincia = $this->validarProvincia($data['provincia'] ?? '');
        if (!empty($erroresProvincia)) {
            $errores['provincia'] = $erroresProvincia;
        }

        // Localidad
        $erroresLocalidad = $this->validarLocalidad($data['localidad'] ?? '');
        if (!empty($erroresLocalidad)) {
            $errores['localidad'] = $erroresLocalidad;
        }

        // Nombre persona contacto
        $erroresNombrePersona = $this->validarNombre($data['nombre_persona'] ?? '');
        if (!empty($erroresNombrePersona)) {
            $errores['nombre_persona'] = $erroresNombrePersona;
        }

        // Teléfono persona contacto
        $erroresTelefonoPersona = $this->validarTelefono($data['telefono_persona'] ?? '');
        if (!empty($erroresTelefonoPersona)) {
            $errores['telefono_persona'] = $erroresTelefonoPersona;
        }

        // Repetido (correo o teléfono duplicado)
        if ($this->correoExiste($data['email'] ?? '') || $this->telefonoExiste($data['telefono'] ?? '')) {
            $errores['repetido'][] = "El correo electrónico y/o teléfono ya está registrado.";
        }

        return $errores;
    }

    public function validarEmpresaAdminEditar($data, $nombreUsuario)
    {
        $errores = [];
        // Email
        $erroresEmail = $this->validarCorreoElectronico($data['email'] ?? '');
        if (!empty($erroresEmail)) {
            $errores['email'] = $erroresEmail;
        }

        // Nombre empresa
        $erroresNombre = $this->validarNombre($data['nombre'] ?? '');
        if (!empty($erroresNombre)) {
            $errores['nombre'] = $erroresNombre;
        }

        // Teléfono empresa
        $erroresTelefono = $this->validarTelefono($data['telefono'] ?? '');
        if (!empty($erroresTelefono)) {
            $errores['telefono'] = $erroresTelefono;
        }

        // Dirección
        $erroresDireccion = $this->validarDireccion($data['direccion'] ?? '');
        if (!empty($erroresDireccion)) {
            $errores['direccion'] = $erroresDireccion;
        }

        // Nombre persona contacto
        $erroresNombrePersona = $this->validarNombre($data['nombre_persona'] ?? '');
        if (!empty($erroresNombrePersona)) {
            $errores['nombre_persona'] = $erroresNombrePersona;
        }

        // Teléfono persona contacto
        $erroresTelefonoPersona = $this->validarTelefono($data['telefono_persona'] ?? '');
        if (!empty($erroresTelefonoPersona)) {
            $errores['telefono_persona'] = $erroresTelefonoPersona;
        }

        if (isset($data["email"]) && $data["email"] !== $nombreUsuario) {
            if ($this->correoExiste($data['email'] ?? '')) {
                $errores['repetido'][] = "El correo electrónico ya está registrado.";
            }
        }

        return $errores;
    }

    public function validarOferta($data)
    {
        $errores = [];

        // Título
        $erroresTitulo = $this->validarTitulo(trim($data['titulo'] ?? ''));
        if (!empty($erroresTitulo)) {
            $errores['titulo'] = $erroresTitulo;
        }

        // Descripción
        $erroresDescripcion = $this->validarDescripcion(trim($data['descripcion'] ?? ''), 1000);
        if (!empty($erroresDescripcion)) {
            $errores['descripcion'] = $erroresDescripcion;
        }

        //fecha fin oferta
        if (!isset($data['fecha_fiin_oferta']) || empty($data['fecha_fiin_oferta'])) {
            $errores['fecha_fiin_oferta'][] = "La fecha de fin de oferta es obligatoria.";
        } else {
            $erroresFechaFinOferta = $this->fechaPosterior($data['fecha_fiin_oferta'] ?? '');
            if (!empty($erroresFechaFinOferta)) {
                $errores['fecha_fiin_oferta'] = $erroresFechaFinOferta;
            }
        }

        // Ciclos seleccionados
        $erroresCiclos = $this->validarCiclosSeleccionados($data['ciclosSeleccionados'] ?? []);
        if (!empty($erroresCiclos)) {
            $errores['ciclosSeleccionados'] = $erroresCiclos;
        }

        return $errores;
    }


    private function validarTitulo($titulo)
    {
        $errores = [];
        $titulo = trim($titulo);

        if (empty($titulo)) {
            $errores[] = "El título no puede estar vacío.";
        } elseif (strlen($titulo) < 5 || strlen($titulo) > 255) {
            $errores[] = "El título debe tener entre 5 y 255 caracteres.";
        }

        return $errores;
    }

    private function fechaPosterior($fecha)
    {
        $errores = [];
        try {
            $fechaActual = new DateTime('today');
            $fechaIngresada = new DateTime($fecha);

            if ($fechaIngresada <= $fechaActual) {
                $errores[] = "La fecha debe ser posterior a la fecha actual.";
            }
        } catch (\Exception $e) {
            $errores[] = "El formato de la fecha es inválido.";
        }
        return $errores;
    }

    private function validarCiclosSeleccionados($ciclos)
    {
        $errores = [];

        if (empty($ciclos)) {
            $errores[] = "Debe seleccionar al menos un ciclo.";
        }

        return $errores;
    }
}
