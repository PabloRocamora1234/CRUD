<?php
require_once __DIR__ . '/interfaces/IToJson.php';
require_once __DIR__ . '/models/Element.php';

use models\Element;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';
    $estado = $_POST['estado'] ?? 'inactivo';
    $prioridad = $_POST['prioridad'] ?? 'baja';

    
    $element = new Element($nombre, $descripcion, $numero_serie, $estado, $prioridad);

   
    $file = 'elementos.txt';

    
    if (file_exists($file)) {
        $currentData = file_get_contents($file);
    } else {
        $currentData = ""; 
    }

    
    $currentData .= $element->toJson() . PHP_EOL;

    
    file_put_contents($file, $currentData);

    
    echo $element->toJson();
} else {
    echo "Método no permitido.";
}
?>