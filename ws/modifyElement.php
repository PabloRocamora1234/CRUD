<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

// Crear instancia del manejador
$manager = new ElementManager();

// Obtener el ID del registro
$id = $_POST['id'] ?? null;

// Verificar que el ID no sea nulo
if (!$id) {
    $response = [
        'success' => false,
        'message' => 'El ID del elemento es obligatorio.',
    ];
    header('Content-Type: application/json');
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit;
}

// Construir arreglo de datos con valores definidos o NULL
$data = [
    'nombre' => $_POST['nombre'] ?? null,
    'descripcion' => $_POST['descripcion'] ?? null,
    'nserie' => $_POST['nserie'] ?? null,
    'estado' => $_POST['estado'] ?? null,
    'prioridad' => $_POST['prioridad'] ?? null,
];

// Asegurarse de que haya al menos un dato para actualizar
if (empty(array_filter($data))) {
    $response = [
        'success' => false,
        'message' => 'No se han proporcionado datos válidos para actualizar.',
    ];
    header('Content-Type: application/json');
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit;
}

try {
    // Actualizar elemento
    $manager->updateElement($id, $data);

    // Respuesta exitosa
    $response = [
        'success' => true,
        'message' => 'Elemento modificado correctamente.',
    ];
} catch (Exception $e) {
    // Manejar errores
    $response = [
        'success' => false,
        'message' => 'Error al modificar el elemento: ' . $e->getMessage(),
    ];
    http_response_code(500); // Internal Server Error
}

// Responder en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>