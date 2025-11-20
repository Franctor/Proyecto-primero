<?php
$archivo = $_GET['file'] ?? '';

// Sanitizar
$archivo = str_replace(['..', '\\'], '', $archivo);

// Subir tres niveles en vez de dos (sale de api → assets → public)
$path = __DIR__ . '/../../../' . $archivo;

if (file_exists($path) && is_file($path)) {
    $info = getimagesize($path);
    if ($info) {
        header('Content-Type: ' . $info['mime']);
        readfile($path);
    }
}

http_response_code(404);
