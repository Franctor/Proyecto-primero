<?php

spl_autoload_register(function ($clase) {

    $clase = str_replace('App\\', '', $clase);
    $clase = str_replace('\\', '/', $clase);

    $fichero = __DIR__ . '/../' . $clase . '.php';

    if (file_exists($fichero)) {
        require_once($fichero);
    } else { //try
        echo "<!-- NO ENCONTRADO: $fichero -->";
    }
});