<?php
namespace models;

use PDO;
use models\Element;

require_once 'Element.php';

class ElementManager {
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

    public function getAllElements() {
        $db = DB::getInstance();
        $stmt = $db->query('SELECT * FROM elementos');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new Element($row);
        }, $rows);
    }


    public function createElement($data) {
        $db = DB::getInstance();
        $stmt = $db->prepare('INSERT INTO elementos (nombre, descripcion, nserie, estado, prioridad) VALUES (:nombre, :descripcion, :nserie, :estado, :prioridad)');
        $stmt->execute($data);
    }

    public function updateElement($id, $data) {
        $db = DB::getInstance();
    
        $fields = [];
        $params = ['id' => $id];
    
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
    
        if (empty($fields)) {
            throw new \Exception('No hay datos válidos para actualizar.');
        }
    
        $sql = 'UPDATE elementos SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
    
        try {
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception("Error al actualizar el elemento: " . $e->getMessage());
        }
    }

    public function deleteElement($id) {
        $db = DB::getInstance();

        $query = "DELETE FROM elementos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('No se pudo eliminar el elemento.');
        }
    }

}
?>