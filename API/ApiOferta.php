<?php

namespace API;

use Exception;
use helpers\Converter;
use services\OfertaService;


require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$idOferta = isset($_GET['idOferta']) ? intval($_GET['idOferta']) : null;
$ofertaService = new OfertaService();
// Si viene un campo "_method" en el POST, lo usamos para sobrescribir el método real
if ($method === 'POST' && isset($input['_method'])) {
    $method = strtoupper($input['_method']);
    unset($input['_method']);
}

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
            if (isset($_GET['empresaId'])) {
                getOfertasEmpresa($_GET['empresaId'], $ofertaService);
            }
            break;

        case 'DELETE':
            // Lógica para manejar las solicitudes DELETE
            if ($idOferta !== null) {
                deleteOferta($idOferta, $ofertaService);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de solicitud requerido para eliminar']);
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

function deleteOferta($id, $ofertaService)
{
    try {
        $ofertaService->eliminarOferta($id);
        http_response_code(200);
        echo json_encode(['success' => 'true']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar la solicitud']);
    }
}

function getOfertasEmpresa($idEmpresa, $ofertaService)
{
    $ofertas = $ofertaService->getOfertasByEmpresaIdAPI($idEmpresa);
    if (!empty($ofertas)) {
        $converter = new Converter();

        $ofertas = $converter->convertirOfertasAJson($ofertas);
        echo json_encode($ofertas);
    } else {
        http_response_code(404);
        echo json_encode(['success' => 'false']);
    }
}

