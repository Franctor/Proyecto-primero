<?php

namespace API;

use Exception;
use helpers\Session;
use helpers\Converter;
use helpers\Validator;
use services\EmpresaService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$empresaService = new EmpresaService();
$idEmpresa = isset($_GET['id']) ? intval($_GET['id']) : null;
// Si viene un campo "_method" en el POST, lo usamos para sobrescribir el método real
if ($method === 'POST' && isset($input['_method'])) {
    $method = strtoupper($input['_method']);
    unset($input['_method']);
}

try {
    switch ($method) {
        case 'GET':
            // Si viene ?me=true, obtener el alumno de la sesión
            if (isset($_GET['me']) && $_GET['me'] === 'true') {
                getEmpresaActual($empresaService);
            }
            break;

        case 'POST':
            break;

        case 'PUT':    
            break;

        case 'DELETE':
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


function getEmpresaActual($empresaService) {
    if (Session::isLogged()) {
        $empresaId = Session::get('perfil')->getId();
        $empresa = $empresaService->getEmpresa($empresaId);
        if ($empresa != null) {
            echo json_encode($empresa);
        } else {
            http_response_code(404);
            echo json_encode(['error'=> 'Empresa no encontrada']);
        }
    }else {
        http_response_code(404);
        echo json_encode(['error'=> 'No autenticado']);
    }
}
