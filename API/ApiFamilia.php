<?php
namespace API;
use Exception;
use services\FamiliaService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$idFamilia = isset($_GET['id']) ? intval($_GET['id']) : null;
$familiaService = new FamiliaService();

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
            if ($idFamilia !== null) {
                getFamilia($idFamilia, $familiaService);
            } else {
                getFamilias($familiaService);
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

// Métodos de la api de Familia
function getFamilia($idFamilia, $familiaService)
{
    $familia = $familiaService->getFamilia($idFamilia);
    if ($familia !== null) {
        http_response_code(200);
        echo json_encode($familia);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Familia no encontrada']);
    }
}

function getFamilias($familiaService)
{
    $familias = $familiaService->getFamilias();
    if (!empty($familias)) {
        http_response_code(200);
        echo json_encode($familias);
    }else {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontraron familias']);
    }
}