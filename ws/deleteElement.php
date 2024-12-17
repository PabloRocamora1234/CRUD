<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$manager = new ElementManager();

// Obtener los datos del JSON (id del sensor a eliminar)
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que el 'id' esté presente
if (!isset($data['id'])) {
    // Si no se proporciona el id, respondemos con un error
    $response = [
        'success' => false,
        'message' => 'ID no proporcionado.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Verificar que el 'id' recibido no esté vacío
if (empty($data['id'])) {
    $response = [
        'success' => false,
        'message' => 'El ID está vacío.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

var_dump($data); // Verifica el contenido del JSON

// Eliminar el sensor usando el id proporcionado
try {
    $manager->deleteElement($data['id']);

    // Si la eliminación fue exitosa, respondemos con un mensaje de éxito
    $response = [
        'success' => true,
        'message' => 'Sensor eliminado correctamente'
    ];
} catch (Exception $e) {
    // Si ocurrió un error durante la eliminación
    $response = [
        'success' => false,
        'message' => 'Error al eliminar el sensor: ' . $e->getMessage()
    ];
}

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);