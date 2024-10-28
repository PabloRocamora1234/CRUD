<?php
require_once 'Database/Database.php';
require_once 'models/Element.php';
require_once 'models/ElementManager.php';

use Models\Element;
use Models\ElementManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';
    $estado = $_POST['estado'] ?? 'inactivo';
    $prioridad = $_POST['prioridad'] ?? 'baja';

    $element = new Element($nombre, $descripcion, $numero_serie, $estado, $prioridad);
    $elementManager = new ElementManager();

    if ($elementManager->createElement($element)) {
        echo json_encode([
            'success' => true,
            'message' => 'Elemento creado correctamente.',
            'data' => $element->toJson()
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear el elemento.',
            'data' => null
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.',
        'data' => null
    ]);
}
?>