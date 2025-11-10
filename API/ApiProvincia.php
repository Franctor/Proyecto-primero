<?php
namespace API;
use Exception;
use services\ProvinciaService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$idProvincia = isset($_GET['id']) ? intval($_GET['id']) : null;
$idLocalidad = isset($_GET['localidad_id']) ? intval($_GET['localidad_id']) : null;
$provinciaService = new ProvinciaService();

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
            if ($idProvincia !== null) {
                getProvincia($idProvincia, $provinciaService);
            } else {
                getProvincias($provinciaService);
            }
            break;
        default:
            // Método no soportado
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
}

// Métodos de la api de Provincia
function getProvincia($idLocalidad, $provinciaService)
{
    $provincia = $provinciaService->getProvinciaById($idLocalidad);
    if ($provincia !== null) {
        http_response_code(200);
        echo json_encode($provincia);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Provincia no encontrada']);
    }
}

function getProvincias($provinciaService)
{
    $provincias = $provinciaService->getAllProvincias();
    if (!empty($provincias)) {
        http_response_code(200);
        echo json_encode($provincias);
    }else {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontraron provincias']);
    }
}