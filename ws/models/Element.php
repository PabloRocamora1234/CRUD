<?php
namespace Models;

use Interfaces\IToJson;

class Element implements IToJson {
    private $nombre;
    private $descripcion;
    private $numero_serie;
    private $estado;
    private $prioridad;

    
    public function __construct($nombre, $descripcion, $numero_serie, $estado, $prioridad) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->numero_serie = $numero_serie;
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
        return $this->numero_serie;
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

    public function setNumeroSerie($numero_serie) {
        $this->numero_serie = $numero_serie;
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
            'numero_serie' => $this->numero_serie,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad
        ]);
    }
}
?>