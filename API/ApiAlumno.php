<?php

namespace API;

use Exception;
use helpers\Converter;
use helpers\Validator;
use services\AlumnoService;

require_once __DIR__ . '/../public/autoload.php';

// Router para las peticiones de Alumno
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = $_POST; // datos del formulario
$files = $_FILES; // archivos subidos
$alumnoService = new AlumnoService();
$idAlumno = isset($_GET['id']) ? intval($_GET['id']) : null;
// Si viene un campo "_method" en el POST, lo usamos para sobrescribir el método real
if ($method === 'POST' && isset($input['_method'])) {
    $method = strtoupper($input['_method']);
    unset($input['_method']);
}

try {
    switch ($method) {
        case 'GET':
            // Lógica para manejar las solicitudes GET
            if ($idAlumno !== null) {
                getAlumno($idAlumno, $alumnoService);
            } else {
                getAlumnos($alumnoService);
            }
            break;

        case 'POST':
            // Lógica para manejar las solicitudes POST
            createAlumno($input, $files, $alumnoService);
            break;

        case 'PUT':
            // Lógica para manejar las solicitudes PUT
            if ($idAlumno !== null) {
                updateAlumno($idAlumno, $input, $files, $alumnoService);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de alumno requerido para actualizar']);
            }
            break;

        case 'DELETE':
            // Lógica para manejar las solicitudes DELETE
            if ($idAlumno !== null) {
                deleteAlumno($idAlumno, $alumnoService);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de alumno requerido para eliminar']);
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

// Métodos de la api de Alumno
function getAlumno($idAlumno, $alumnoService)
{
    $alumno = $alumnoService->getAlumno($idAlumno);
    if ($alumno != null) {
        echo json_encode($alumno);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Alumno no encontrado']);
    }
}

function getAlumnos($alumnoService)
{
    $alumnos = $alumnoService->getAlumnos();
    if (!empty($alumnos)) {
        echo json_encode($alumnos);
    } else {
        http_response_code(404);
            echo json_encode(['respuesta' => false]);
    }
}

function createAlumno($input, $files, $alumnoService)
{
    $validate = new Validator();
    if ($validate->validarAlumno($input, $files)) {
        $alumno = $alumnoService->createAlumno($input, $files);
    } else {
        $alumno = null;
    }

    if ($alumno != null) {
        $converter = new Converter();
        $alumno = $converter->convertirAlumnoAJson($alumno);
        http_response_code(201);
        echo json_encode($alumno);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Error al crear el alumno']);
    }
}

function updateAlumno($idAlumno, $input, $files, $alumnoService)
{
    $validate = new Validator();
    if ($validate->validarAlumnoEditar($input, $files)) {
        $alumno = $alumnoService->updateAlumno($idAlumno, $input, $files);
        $converter = new Converter();
        $alumno = $converter->convertirAlumnoAJson($alumno);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Error de validación']);
    }

    if ($alumno != null) {
        http_response_code(200);
        echo json_encode($alumno);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Alumno no encontrado']);
    }
}

function deleteAlumno($idAlumno, $alumnoService)
{
    $result = $alumnoService->deleteAlumno($idAlumno);
    if ($result) {
        http_response_code(204);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Alumno no encontrado']);
    }
}