<?php
namespace models;

use Interfaces\ITOJSON;
require_once 'interfaces/ITOJSON.php';

class Element implements ITOJSON {
    private $id;
    private $nombre;
    private $descripcion;
    private $nserie;
    private $estado;
    private $prioridad;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->nserie = $data['nserie'] ?? '';
        $this->estado = $data['estado'] ?? '';
        $this->prioridad = $data['prioridad'] ?? '';
    }

    public function getId() {
        return $this->id;
    }

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

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setNumeroSerie($nserie) {
        $this->nserie = $nserie;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }

    public function toJson() {
        return json_encode([
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'nserie' => $this->nserie,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad
        ]);
    }
}
?>