<?php

namespace habitacion;

class Habitacion{
    
    private $id;
    private $m2;
    private $ventana;
    private $tipo;
    private $limpieza;
    private $internet;
    private $precio;
    
    
    public function __construct($id=null,$m2,$ventana,$tipo,$limpieza,$internet,$precio) {
        if($id != null){
            $this->id = $id;
        }
        $this->m2 = $m2;
        $this->ventana = $ventana;
        $this->tipo = $tipo;
        $this->limpieza = $limpieza;
        $this->internet = $internet;
        $this->precio = $precio;
        
    }
    
    public function __get($var) {
       if(property_exists($this, $var)){
            return $this->$var;
        }
    }
    
    /**public function __set($var, $value) {
        $this->$var = $value;
        
    }*/
}

?>