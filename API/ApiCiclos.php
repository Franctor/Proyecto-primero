<?php
namespace API;
use Exception;
use services\CicloService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$idCiclo = isset($_GET['id']) ? intval($_GET['id']) : null;
$idFamilia = isset($_GET['familia_id']) ? intval($_GET['familia_id']) : null;
$cicloService = new CicloService();

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
            if ($idCiclo !== null) {
                getCiclo($idCiclo, $cicloService);
            } else {
                getCiclos($cicloService);
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

// Métodos de la api de Ciclo
function getCiclo($idCiclo, $cicloService)
{
    $ciclo = $cicloService->getCiclo($idCiclo);
    if ($ciclo !== null) {
        http_response_code(200);
        echo json_encode($ciclo);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ciclo no encontrado']);
    }
}

function getCiclos($cicloService)
{
    $ciclos = $cicloService->getCiclos();
    if (!empty($ciclos)) {
        http_response_code(200);
        echo json_encode($ciclos);
    }else {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontraron ciclos']);
    }
}