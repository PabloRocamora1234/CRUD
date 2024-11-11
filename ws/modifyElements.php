<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';
require_once 'models/Element.php';

use models\DB;
use models\ElementManager;
use models\Element;

$db = DB::getInstance();
$manager = new ElementManager();

$id = $_GET['id'] ?? null;
$params = [
    'nombre' => $_POST['nombre'] ?? '',
    'descripcion' => $_POST['descripcion'] ?? '',
    'nserie' => $_POST['nserie'] ?? '',
    'estado' => $_POST['estado'] ?? 'inactivo',
    'prioridad' => $_POST['prioridad'] ?? 'baja'
];

echo "Datos capturados: " . json_encode($params) . "\n";

if ($id) {
    $existingElement = $manager->getElement($id);
    if ($existingElement) {
        $newElement = new Element(
            $params['nombre'] ?: $existingElement->getNombre(),
            $params['descripcion'] ?: $existingElement->getDescripcion(),
            $params['nserie'] ?: $existingElement->getNumeroSerie(),
            $params['estado'] ?: $existingElement->getEstado(),
            $params['prioridad'] ?: $existingElement->getPrioridad()
        );

        if ($manager->modifyElement($id, $newElement)) {
            $updatedElement = $manager->getElement($id);
            if ($updatedElement) {
                $response = [
                    'success' => true,
                    'message' => 'Elemento modificado correctamente',
                    'data' => $updatedElement->toJson()
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No se pudo obtener el elemento actualizado.',
                    'data' => null
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Error al modificar el elemento en la base de datos.',
                'data' => null
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Elemento no encontrado.',
            'data' => null
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'ID no válido.',
        'data' => null
    ];
}

header('Content-Type: application/json');
echo json_encode($response);