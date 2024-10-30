<?php
namespace models;

use interfaces\ITOJSON;
require_once 'interfaces/ITOJSON.php';

class Element implements IToJson {
    private $nombre;
    private $descripcion;
    private $nserie;
    private $estado;
    private $prioridad;

    public function __construct($nombre, $descripcion, $nserie, $estado, $prioridad) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->nserie = $nserie;
        $this->estado = $estado;
        $this->prioridad = $prioridad;
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
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'nserie' => $this->nserie,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad
        ]);
    }
}