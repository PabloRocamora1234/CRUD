<?php
// ElementManager.php
namespace models;

require_once 'DB.php';

class ElementManager {
    private $connection;

    public function __construct() {
        $this->connection = DB::getInstance();
    }

    public function createElement(Element $element): bool {
        $sql = "INSERT INTO elementos (nombre, descripcion, nserie, estado, prioridad) VALUES (:nombre, :descripcion, :nserie, :estado, :prioridad)";
        $stmt = $this->connection->prepare($sql);

        // Asignación de valores a variables
        $nombre = $element->getNombre();
        $descripcion = $element->getDescripcion();
        $nserie = $element->getNumeroSerie();
        $estado = $element->getEstado();
        $prioridad = $element->getPrioridad();

        // Debugging: verificar valores antes de insertar
        if (empty($nserie)) {
            throw new \Exception("El número de serie no puede estar vacío.");
        }

        // Uso de bindParam
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':nserie', $nserie);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':prioridad', $prioridad);

        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Error en la inserción: " . $e->getMessage();
            return false;
        }
    }

    public function getElement(int $id): ?Element {
        $sql = "SELECT * FROM elementos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch();
        if ($row) {
            return new Element($row['nombre'], $row['descripcion'], $row['nserie'], $row['estado'], $row['prioridad']);
        }

        return null;
    }

    public function deleteElement(int $id): ?Element {
        $element = $this->getElement($id);
        if ($element) {
            $sql = "DELETE FROM elementos WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $element;
        }

        return null;
    }

    public function modifyElement(int $id, Element $element): bool {
        $sql = "UPDATE elementos SET nombre = :nombre, descripcion = :descripcion, nserie = :nserie, estado = :estado, prioridad = :prioridad WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        // Asignación de valores a variables
        $nombre = $element->getNombre();
        $descripcion = $element->getDescripcion();
        $nserie = $element->getNumeroSerie();
        $estado = $element->getEstado();
        $prioridad = $element->getPrioridad();

        // Uso de bindParam
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':nserie', $nserie);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':prioridad', $prioridad);

        return $stmt->execute();
    }

    public function getAllElements(): array {
        $sql = "SELECT * FROM elementos";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $elements = [];
        while ($row = $stmt->fetch()) {
            $elements[] = new Element($row['nombre'], $row['descripcion'], $row['nserie'], $row['estado'], $row['prioridad']);
        }

        return $elements;
    }
}