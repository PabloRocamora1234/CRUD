<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    $elements = $manager->getAllElements();
    $response['success'] = true;
    $response['message'] = 'Todos los elementos obtenidos';
    $response['data'] = array_map(function($element) {
        return json_decode($element->toJson(), true);
    }, $elements);
} catch (Exception $e) {
    $response['message'] = 'Error al obtener los elementos: ' . $e->getMessage();
    error_log('Error en getElement.php: ' . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
?>