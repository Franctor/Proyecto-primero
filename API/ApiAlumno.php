<?php
//Router para las peticiones de Alumno
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Lógica para manejar las solicitudes GET
} elseif ($method == 'POST') {
    // Lógica para manejar las solicitudes POST
} elseif ($method == 'PUT') {
    // Lógica para manejar las solicitudes PUT
} elseif ($method == 'DELETE') {
    // Lógica para manejar las solicitudes DELETE
} else {
    // Método no soportado
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

//Métodos de la api de Alumno
function getAlumno() {

}

function createAlumno() {

}

function updateAlumno() {

}

function deleteAlumno() {

}