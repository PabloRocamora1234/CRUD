<?php
namespace models;

use PDO;
use models\Element;

require_once 'Element.php';

class ElementManager {
    // Obtener un elemento por ID
    public function getElement($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM elementos WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Element($row);
        }
        return null;
    }

    // Obtener todos los elementos
    public function getAllElements() {
        $db = DB::getInstance();
        $stmt = $db->query('SELECT * FROM elementos');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new Element($row);
        }, $rows);
    }

    // Crear un nuevo elemento
    public function createElement($data) {
        $db = DB::getInstance();
        $stmt = $db->prepare('INSERT INTO elementos (nombre, descripcion, nserie, estado, prioridad) VALUES (:nombre, :descripcion, :nserie, :estado, :prioridad)');
        $stmt->execute($data);
    }

    // Actualizar un elemento existente
    public function updateElement($id, $data) {
        $db = DB::getInstance();
    
        // Filtrar los campos no vacíos o definidos
        $fields = [];
        $params = ['id' => $id];
    
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') { // Solo incluir campos no vacíos
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
    
        // Verificar que hay al menos un campo para actualizar
        if (empty($fields)) {
            throw new \Exception('No hay datos válidos para actualizar.');
        }
    
        // Construir la consulta dinámica
        $sql = 'UPDATE elementos SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
    
        try {
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el elemento: " . $e->getMessage());
        }
    }

    // Eliminar un elemento por ID
    public function deleteElement($id) {
        // Conectar a la base de datos
        $db = DB::getInstance();  // Asegúrate de que sea DB::getInstance(), no DB::getConnection()

        // Preparar y ejecutar la consulta para eliminar el elemento
        $query = "DELETE FROM elementos WHERE id = :id";  // Cambia sensores a elementos
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('No se pudo eliminar el elemento.');
        }
    }

}
?>