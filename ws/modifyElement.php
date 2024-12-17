<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;

if (!$id) {
    $response = [
        'success' => false,
        'message' => 'El ID del elemento es obligatorio.',
    ];
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit;
}

$data = [
    'nombre' => $data['nombre'] ?? null,
    'descripcion' => $data['descripcion'] ?? null,
    'nserie' => $data['nserie'] ?? null,
    'estado' => $data['estado'] ?? null,
    'prioridad' => $data['prioridad'] ?? null,
];

if (empty(array_filter($data))) {
    $response = [
        'success' => false,
        'message' => 'No se han proporcionado datos válidos para actualizar.',
    ];
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit;
}

try {
    $manager->updateElement($id, $data);

    $response = [
        'success' => true,
        'message' => 'Elemento modificado correctamente.',
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error al modificar el elemento: ' . $e->getMessage(),
    ];
    http_response_code(500);
}

header('Content-Type: application/json');
echo json_encode($response);
?>