<?php
namespace models;

require_once 'Element.php';

class ElementManager {
    private $connection;

    public function __construct() {
        $this->connection = DB::getInstance();
    }

    public function createElement(Element $element): bool {
        $sql = "INSERT INTO elementos (nombre, descripcion, nserie, estado, prioridad) VALUES (:nombre, :descripcion, :nserie, :estado, :prioridad)";
        $stmt = $this->connection->prepare($sql);

        
        $nombre = $element->getNombre();
        $descripcion = $element->getDescripcion();
        $nserie = $element->getNumeroSerie();
        $estado = $element->getEstado();
        $prioridad = $element->getPrioridad();

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':nserie', $nserie);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':prioridad', $prioridad);

        return $stmt->execute();
    }

    public function getElement(int $id): ?Element {
        $sql = "SELECT * FROM elementos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch();
        if ($row) {
            return new Element($row['nombre'], $row['descripcion'], $row['nserie'], $row['estado'], $row['prioridad']); // Cambiado a nserie
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
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $element->getNombre());
        $stmt->bindParam(':descripcion', $element->getDescripcion());
        $stmt->bindParam(':nserie', $element->getNumeroSerie());
        $stmt->bindParam(':estado', $element->getEstado());
        $stmt->bindParam(':prioridad', $element->getPrioridad());

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