<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use Models\ElementManager;

$db = DB::getInstance();
$manager = new ElementManager($db);

$id = $_GET['id'] ?? null;
$params = [
    'nombre' => $_POST['nombre'] ?? null,
    'descripcion' => $_POST['descripcion'] ?? null,
    'numero_serie' => $_POST['numero_serie'] ?? null,
    'estado' => $_POST['estado'] ?? null,
    'prioridad' => $_POST['prioridad'] ?? null
];

if ($id && $manager->modifyElement($id, new Element(
        $params['nombre'] ?? '', 
        $params['descripcion'] ?? '', 
        $params['numero_serie'] ?? '', 
        $params['estado'] ?? 'inactivo', 
        $params['prioridad'] ?? 'baja'
    ))) {
    $response = [
        'success' => true,
        'message' => 'Elemento modificado correctamente',
        'data' => $manager->getElement($id)->toJson()
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Error al modificar el elemento o el ID no es válido',
        'data' => null
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>