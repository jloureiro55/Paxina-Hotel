<?php

namespace usuario;

class Usuario{
    
    private $nombre;
    private $email;
    private $telf;
    private $direccion;
    private $num_rol;
    private $nombre_rol;
    
   public function __construct($nombre,$email,$telf,$direccion,$rol, $nombre_rol) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->telf = $telf;
        $this->direccion = $direccion;
        $this->num_rol = $rol;
        $this->nombre_rol = $nombre_rol;
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