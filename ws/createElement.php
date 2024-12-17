<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();

$data = [
    'nombre' => $_POST['nombre'],
    'descripcion' => $_POST['descripcion'],
    'nserie' => $_POST['nserie'],
    'estado' => isset($_POST['estado']) ? 'activo' : 'inactivo',
    'prioridad' => $_POST['prioridad']
];

try {
    $manager->createElement($data);
    $response = [
        'success' => true,
        'message' => 'Sensor creado correctamente',
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Hubo un error al crear el sensor: ' . $e->getMessage(),
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>