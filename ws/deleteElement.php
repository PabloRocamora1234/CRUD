<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    $response = [
        'success' => false,
        'message' => 'ID no proporcionado.',
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$id = $data['id'];

try {
    $manager->deleteElement($id);

    $response = [
        'success' => true,
        'message' => 'Elemento eliminado correctamente.',
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error al eliminar el elemento: ' . $e->getMessage(),
    ];
    http_response_code(500);
}

header('Content-Type: application/json');
echo json_encode($response);