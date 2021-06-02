<?php

namespace usuario;

class Usuario{
    private $id;
    private $nombre;
    private $email;
    private $telf;
    private $direccion;
    private $rol;
    
   public function __construct($id,$nombre,$email,$telf,$direccion,$rol) {
        $this->id=$id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->telf = $telf;
        $this->direccion = $direccion;
        $this->rol = $rol;
    }
    
    public function __get($var) {
        if(property_exists($this, $var)){
            return $this->$var;
        }
      
    }
    
    public function __set($var, $value) {
        $this->$var = $value;
        
    }
    
    
}

?>