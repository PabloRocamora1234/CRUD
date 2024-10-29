<?php
require_once 'models/DB.php';
require_once 'models/ElementManager.php';

use models\DB;
use models\ElementManager;

$db = DB::getInstance();
$manager = new ElementManager($db);

$id = $_GET['id'] ?? null;
if ($id && $manager->deleteElement((int)$id)) {
    $response = [
        'success' => true,
        'message' => 'Elemento eliminado correctamente',
        'data' => null
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Error al eliminar el elemento o ID no proporcionado',
        'data' => null
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>