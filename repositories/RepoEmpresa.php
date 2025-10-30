<?php
class RepoEmpresa {
    public function save(Empresa $empresa) {
        $conn = Connection::getConnection();
        $stmt = $conn->prepare("INSERT INTO empresas (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, verificada, descripcion) VALUES (:nombre, :telefono, :direccion, :nombre_persona, :telefono_persona, :logo, :verificada, :descripcion)");
        $stmt->bindParam(':nombre', $empresa->nombre);
        $stmt->bindParam(':telefono', $empresa->telefono);
        $stmt->bindParam(':direccion', $empresa->direccion);
        $stmt->bindParam(':nombre_persona', $empresa->nombre_persona);
        $stmt->bindParam(':telefono_persona', $empresa->telefono_persona);
        $stmt->bindParam(':logo', $empresa->logo);
        $stmt->bindParam(':verificada', $empresa->verificada);
        $stmt->bindParam(':descripcion', $empresa->descripcion);
        $stmt->execute();
    }
}