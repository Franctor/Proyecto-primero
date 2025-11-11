<?php

spl_autoload_register(function ($clase) {
    try {
        $clase = str_replace('\\', '/', $clase);
        $fichero = __DIR__ . '/../' . $clase . '.php';

        if (!file_exists($fichero)) {
            throw new Exception("Clase no encontrada: $fichero");
        }

        require_once($fichero);
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
});