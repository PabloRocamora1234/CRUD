<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();

$id = $_GET['id'] ?? null;

if ($id) {
    $result = $manager->getElement($id);
    
    if ($result) {
        $response = [
            'success' => true,
            'message' => 'Elemento obtenido',
            'data' => $result->toJson()
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Elemento no encontrado',
            'data' => null
        ];
    }
} else {
    $result = $manager->getAllElements();

    $response = [
        'success' => true,
        'message' => 'Todos los elementos obtenidos',
        'data' => array_map(function($element) {
            return $element->toJson();
        }, $result)
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>