<?php
require_once 'models/DB.php';
require_once 'models/Element.php';
require_once 'models/ElementManager.php';

use models\Element;
use models\ElementManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $nserie = $_POST['nserie'] ?? '';
    $estado = $_POST['estado'] ?? 'inactivo';
    $prioridad = $_POST['prioridad'] ?? 'baja';

    if (empty($nserie)) {
        echo json_encode([
            'success' => false,
            'message' => 'Número de serie no puede estar vacío.',
            'data' => null
        ]);
        exit;
    }

    $element = new Element($nombre, $descripcion, $nserie, $estado, $prioridad);
    $elementManager = new ElementManager();

    try {
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
    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
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