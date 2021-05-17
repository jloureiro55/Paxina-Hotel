<?php

function loadBBDD() {
    try {
        $res = leer_config(dirname(__FILE__) . "/config/configurationBD.xml", dirname(__FILE__) . "/config/configurationBD.xsd");
        $bd = new PDO($res[0], $res[1], $res[2]);
        return $bd;
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit();
    }
}

function leer_config($fichero_config_BBDD, $esquema) {
    /*
     * $fichero_config_BBDD es la ruta del fichero con los datos de conexión a la BBDD
     * $esquema es la ruta del fichero XSD para validar la estructura del fichero anterior
     * Si el fichero de configuración existe y es válido, devuelve un array con tres
     * valores: la cadena de conexión, el nombre de usuario y la clave.
     * Si no encuentra el fichero o no es válido, lanza una excepción.
     */

    $config = new DOMDocument();
    $config->load($fichero_config_BBDD);
    $res = $config->schemaValidate($esquema);
    if ($res === FALSE) {
        throw new InvalidArgumentException("Revise el fichero de configuración");
    }
    $datos = simplexml_load_file($fichero_config_BBDD);
    $ip = $datos->xpath("//ip");
    $nombre = $datos->xpath("//nombre");
    $usu = $datos->xpath("//usuario");
    $clave = $datos->xpath("//clave");
    $cad = sprintf("mysql:dbname=%s;host=%s", $nombre[0], $ip[0]);
    $resul = [];
    $resul[] = $cad;
    $resul[] = $usu[0];
    $resul[] = $clave[0];
    return $resul;
}

function check_user($nombre, $clave) {
    require_once '/functions/functions.php';
    /*
     * Comprueba los datos que recibe del formulario del login. Si los datos son correctos
     * devuelve un array con dos campos: codRes (el código del restaurante) y correo 
     * con su correo. En caso de error devuelve false
     */
    try{
         $devol = FALSE;
    $bd = loadBBDD();
    $sql = "select  password,rol,telf from usuarios where nombre="+$nombre;
    $statement = $bd->prepare($sql);
    if($statement->execute(array($nombre))){
        $row = $statement->fetch();
        if($row){
            $hashed = $row['password'];
            $rol = $row['rol'];
            if(validatePass($clave, $hashed)){
                return array($nombre,$rol);
            }else{
                return false;
            }
        }
    }
     } catch (Exception $ex) {
         echo "Error ".$ex->getCode()." ".$ex->getMessage();
     }finally {
         $statement = null;
         $bd = null;
     }
    
}



?>