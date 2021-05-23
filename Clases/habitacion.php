<?php

class Habitacion{
    private $id;
    private $m2;
    private $ventana;
    private $tipo;
    private $limpieza;
    private $internet;
    private $precio;
    
    public function __construct($m2,$ventana,$tipo,$limpieza,$internet,$precio) {
        $this->m2=$m2;
        $this->ventana=$ventana;
        $this->tipo=$tipo;
        $this->limpieza=$limpieza;
        $this->internet=$internet;
        $this->precio=$precio;
    }
    
    public function __get($var) {
        return $this->$var;
    }
    
    public function __set($var,$value){
        $this->$var=$value;
    }
    
}

