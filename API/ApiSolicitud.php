<?php

namespace API;

use Exception;
use helpers\Converter;
use services\SolicitudService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$idSolicitud = isset($_GET['id']) ? intval($_GET['id']) : null;
$solicitudService = new SolicitudService();
// Si viene un campo "_method" en el POST, lo usamos para sobrescribir el método real
if ($method === 'POST' && isset($input['_method'])) {
    $method = strtoupper($input['_method']);
    unset($input['_method']);
}

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
        if (isset($_GET['idAlumno'])) {
            getSolicitudesAlumno($_GET['idAlumno'], $solicitudService);
        } elseif ($idSolicitud !== null) {
                getSolicitud($idSolicitud, $solicitudService);
            } else {
                getSolicitudes($solicitudService);
            }
            break;

        case 'PUT':
            // Lógica para manejar las solicitudes DELETE
            if ($idSolicitud !== null) {
                updateSolicitud($idSolicitud, $solicitudService);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de solicitud requerido para eliminar']);
            }
            break;
        case 'DELETE':
            // Lógica para manejar las solicitudes DELETE
            if ($idSolicitud !== null) {
                deleteSolicitud($idSolicitud, $solicitudService);
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

function getSolicitud($id, $solicitudService)
{
    $solicitud = $solicitudService->getSolicitudByIdAPI($id);
    if ($solicitud !== null) {
        $converter = new Converter();
        $solicitud = $converter->convertirSolicitudAJson($solicitud);
        echo json_encode($solicitud);
    } else {
        http_response_code(404);
        echo json_encode(['success'=> 'false']);
    }
}

function getSolicitudes($solicitudService)
{
    $solicitudes = $solicitudService->getAllSolicitudesAPI();
    if (!empty($solicitudes)) {
        $converter = new Converter();
        $solicitudes = $converter->convertirSolicitudesAJson($solicitudes);
        echo json_encode($solicitudes);
    } else {
        http_response_code(404);
        echo json_encode(['success'=> 'false']);
    }
}

function deleteSolicitud($id, $solicitudService)
{
    try {
        $solicitudService->desaplicarOferta($id);
        http_response_code(200);
        echo json_encode(['success' => 'true']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar la solicitud']);
    }
}

function getSolicitudesAlumno($idAlumno, $solicitudService)
{
    $solicitudes = $solicitudService->getSolicitudesByAlumnoIdAPI($idAlumno);
    if (!empty($solicitudes)) {
        $converter = new Converter();
        $solicitudes = $converter->convertirSolicitudesAJson($solicitudes);
        echo json_encode($solicitudes);
    } else {
        http_response_code(404);
        echo json_encode(['success'=> 'false']);;
    }
}

function updateSolicitud($id, $solicitudService) 
{
    $solicitud = $solicitudService->getSolicitudByIdAPI($id);
    if ($solicitud !== null) {
        if (isset($_POST['estado'])) {
            $nuevoEstado = intval($_POST['estado']);
            $solicitud->setEstado($nuevoEstado);
            $updateResult = $solicitudService->updateSolicitud($solicitud);
            if($updateResult) {
                $respuesta = ['success'=> true, 'estado'=> $updateResult->getEstado()];
                http_response_code(200);
                echo json_encode($respuesta);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar la solicitud']);
            }
        }
    }else {
        http_response_code(404);
        echo json_encode(['error' => 'Solicitud no encontrada']);
    }
}

