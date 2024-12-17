<?php
namespace models;

use Interfaces\ITOJSON; // Importamos la interfaz IToJson
require_once 'interfaces/ITOJSON.php'; // Aseguramos que la interfaz esté incluida

class Element implements ITOJSON {
    private $nombre;
    private $descripcion;
    private $nserie;
    private $estado;
    private $prioridad;

    // Constructor que inicializa las propiedades a partir de un array de datos
    public function __construct($data) {
        $this->nombre = $data['nombre'] ?? '';  // Usar ?? para valores por defecto
        $this->descripcion = $data['descripcion'] ?? '';
        $this->nserie = $data['nserie'] ?? '';
        $this->estado = $data['estado'] ?? '';
        $this->prioridad = $data['prioridad'] ?? '';
    }

    // Métodos getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getNumeroSerie() {
        return $this->nserie;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getPrioridad() {
        return $this->prioridad;
    }

    // Métodos setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setNumeroSerie($numero_serie) {
        $this->nserie = $nserie;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }

    // Implementación de la interfaz IToJson
    public function toJson() {
        // Se utiliza json_encode para convertir el objeto a JSON
        return json_encode([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'nserie' => $this->nserie,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad
        ]);
    }
}
?>