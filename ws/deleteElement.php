<?php
require_once 'Database.php';
require_once 'ElementManager.php';

use Database\Database;
use models\ElementManager;

$db = (new Database())->getConnection();
$manager = new ElementManager($db);

$id = $_GET['id'] ?? null;
if ($id && $manager->deleteElement($id)) {
    $response = [
        'success' => true,
        'message' => 'Elemento eliminado correctamente',
        'data' => null
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Error al eliminar el elemento',
        'data' => null
    ];
}

echo json_encode($response);
?>