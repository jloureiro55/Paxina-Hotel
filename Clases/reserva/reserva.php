<?php

namespace reserva;

class Reserva{
    private $num_reserva;
    private $usuario;
    private $fecha_reserva;
    private $dias;
    private $fecha_entrada;
    private $fecha_salida;
    private $habitacion;
    private $servicios;
    
    public function __construct($num_reserva,$usuario,$fecha_reserva,$dias,$fecha_entrada,$fecha_salida,$habitacion,$servicios=null){
        $this->num_reserva = $num_reserva;
        $this->usuario = $usuario;
        $this->fecha_reserva = $fecha_reserva;
        $this->dias = $dias;
        $this->fecha_entrada = $fecha_entrada;
        $this->fecha_salida = $fecha_salida;
        $this->habitacion = $habitacion;
        if($servicios != null){
            $this->servicios = $servicios;
        }
    }
    
    public function __get($name) {
        if(property_exists($this, $name)){
            return $this->$name;
        }
    }
   
    public function __set($var, $value) {
        $this->$var = $value;   
    }
}
?>