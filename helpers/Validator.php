<?php
namespace helpers;

use repositories\RepoUsuario;
use repositories\RepoAlumno;
class Validator
{
    public function validarAlumno($input, $files)
    {
        $nombre = $input['nombre'];
        $apellido = $input['apellido'];
        $telefono = $input['telefono'];
        $direccion = $input['direccion'];
        $foto = $files['foto-perfil'];
        $cv = $files['cv'];
        $nombre_usuario = $input['email'];
        $password = isset($input['password']) ? $input['password'] : null;
        $localidad_id = $input['localidad'];
        // Incluir los ciclos del usuario
        $ciclos = isset($input['ciclosSeleccionados']) ? $input['ciclosSeleccionados'] : [];

        $valido = true;
        if ($this->telefonoExiste($telefono)) {
            $valido = false;
        }
        if ($this->correoExiste($nombre_usuario)) {
            $valido = false;
        }
        if (!$this->validarNombre($nombre)) {
            $valido = false;
        }
        if (!$this->validarNombre($apellido)) {
            $valido = false;
        }
        if (!$this->validarTelefono($telefono)) {
            $valido = false;
        }
        if (!$this->validarDireccion($direccion)) {
            $valido = false;
        }
        if (!$this->validarCorreoElectronico($nombre_usuario)) {
            $valido = false;
        }
        if ($password !== null && !$this->validarPassword($password)) {
            $valido = false;
        }
        if (!$this->validarNumeroEntero($localidad_id)) {
            $valido = false;
        }
        if (!$this->validarFoto($foto)) {
            $valido = false;
        }
        if (!$this->validarCV($cv)) {
            $valido = false;
        }
        return $valido;
    }

    public function validarAlumnoEditar($input, $files)
    {
        $nombre = $input['nombre'];
        $apellido = $input['apellido'];
        $telefono = $input['telefono'];
        $direccion = $input['direccion'];
        $foto = isset($files['foto-perfil']) ? $files['foto-perfil'] : null;
        $cv = isset($files['cv']) ? $files['cv'] : null;
        $nombre_usuario = $input['email'];
        $localidad_id = $input['localidad'];

        $valido = true;
        if (!$this->validarNombre($nombre)) {
            $valido = false;
        }
        if (!$this->validarNombre($apellido)) {
            $valido = false;
        }
        if (!$this->validarTelefono($telefono)) {
            $valido = false;
        }
        if (!$this->validarDireccion($direccion)) {
            $valido = false;
        }
        if (!$this->validarCorreoElectronico($nombre_usuario)) {
            $valido = false;
        }
        if (!$this->validarNumeroEntero($localidad_id)) {
            $valido = false;
        }
        if ($foto && $foto['error'] !== UPLOAD_ERR_NO_FILE && !$this->validarFoto($foto)) {
            $valido = false;
        }
        if ($cv && $cv['error'] !== UPLOAD_ERR_NO_FILE && !$this->validarCV($cv)) {
            $valido = false;
        }
        return $valido;
    }

    //Validar que en un array de ciclos vengan solo numeros enteros y no esten repetidos
    public function validarArrayCiclos($ciclos)
    {
        $valido = true;
        if (!is_array($ciclos)) {
            $valido = false;
        } else {
            
        }
        return $valido;
    }

    public function validarNumeroEntero($numero)
    {
        $numero = trim($numero);
        $valido = filter_var($numero, FILTER_VALIDATE_INT) !== false;
        return $valido;
    }
    public function validarNombre($nombre)
    {
        $nombre = trim($nombre);
        $valido = true;
        if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 45) {
            $valido = false;
        } else if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'.-]+$/", $nombre)) {
            $valido = false;
        }
        return $valido;
    }

    public function validarDireccion($direccion)
    {
        $direccion = trim($direccion);
        $valido = true;
        if (empty($direccion) || strlen($direccion) < 5 || strlen($direccion) > 100) {
            $valido = false;
        } else if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s.,\-\/ºª]+$/", $direccion)) {
            $valido = false;
        }
        return $valido;
    }

    public function validarTelefono($telefono)
    {
        $telefono = trim($telefono);
        $valido = true;
        if (empty($telefono)) {
            $valido = false;
        } else if (!preg_match("/^(?:\+34)?[67]\d{8}$/", $telefono)) {
            $valido = false;
        }
        return $valido;
    }

    public function validarCorreoElectronico($email)
    //Validar tambien que el correo electronico no exista en la base de datos
    {
        $email = trim($email);
        $valido = true;
        if (empty($email) || strlen($email) > 100 || strlen($email) < 5) {
            $valido = false;
        } else if (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
            $valido = false;
        }
        return $valido;
    }

    public function validarFoto($foto)
    {
        $valido = false;
        $tiposFoto = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 1 * 1024 * 1024; // 1MB

        if (
            isset($foto) &&
            $foto['error'] === UPLOAD_ERR_OK &&
            in_array($foto['type'], $tiposFoto) &&
            $foto['size'] <= $maxSize &&
            is_uploaded_file($foto['tmp_name'])
        ) {
            $valido = true;
        }
        return $valido;
    }

    public function validarCV($cv)
    {
        $valido = false;
        $tiposCV = ['application/pdf', 'application/x-pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (
            isset($cv) &&
            $cv['error'] === UPLOAD_ERR_OK &&
            in_array($cv['type'], $tiposCV) &&
            $cv['size'] <= $maxSize &&
            is_uploaded_file($cv['tmp_name'])
        ) {
            $valido = true;
        }
        return $valido;
    }

    public function validarPassword($password)
    {
        $valido = false;

        if (
            isset($password) &&
            strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&      // al menos una mayúscula
            preg_match('/[a-z]/', $password) &&      // al menos una minúscula
            preg_match('/[0-9]/', $password) &&      // al menos un número
            preg_match('/[!@#$%^&*()\-_+=]/', $password) && // al menos un carácter especial
            !preg_match('/\s/', $password)           // no espacios
        ) {
            $valido = true;
        }

        return $valido;
    }

    public function correoExiste($email)
    {
        $existe = false;
        $repoUsuario = new RepoUsuario();
        if ($repoUsuario->findByNombreUsuario($email) !== null) {
            $existe = true;
        }
        return $existe;
    }

    public function telefonoExiste($telefono)
    {
        $existe = false;
        $repoAlumno = new RepoAlumno();
        if ($repoAlumno->findByTelefono($telefono) !== null) {
            $existe = true;
        }
        return $existe;
    }

}