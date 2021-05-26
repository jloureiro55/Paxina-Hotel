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
    require_once 'functions/functions.php';
    /*
     * Comprueba los datos que recibe del formulario del login. Si los datos son correctos
     * devuelve un array con dos campos: codRes (el código del restaurante) y correo 
     * con su correo. En caso de error devuelve false
     */
    try{
     $devol= FALSE;
    $bd = loadBBDD();
    $sql = "select  password,rol,telf from usuarios where nombre=?";
    $statement = $bd->prepare($sql);
    if($statement->execute($nombre)){
        $row = $statement->fetch();
        if($row){
            $hashed = $row['password'];
            $rol = $row['rol'];
            if(validatePass($clave, $hashed)){
                $devol = array($nombre,$rol);
            }else{
                $devol = false;
            }
        }
    }
        return $devol;
     } catch (Exception $ex) {
         echo "Error ".$ex->getCode()." ".$ex->getMessage();
     }finally {
         $statement = null;
         $bd = null;
     }
}

function loadrooms($checkin,$checkout){
    try{
        $bd = loadBBDD();
        $checkin = strtotime($checkin);
        $checkout = strtotime($checkout);
        $sql = "SELECT * FROM `reservas` WHERE `fecha_reserva` BETWEEN :param_entrada AND :param_salida";
        
        $statement=$bd->prepare($sql);
        $statement->bindValue('param_entrada',$checkin, PDO::PARAM_STR);
        $statement->bindValue('param_salida',$checkout,PDO::PARAM_STR);
        if($statement->execute()){
            var_dump($statement);
            $row = $statement->fetch();
            var_dump($row);
            if($row){
                var_dump($row);
            }
        }else{
            echo "error";
        }
        
    } catch (Exception $ex) {
        echo "Error ".$ex->getCode()." ".$ex->getMessage();
    }finally {
         $statement = null;
         $bd = null;
     }
}

    function loadreserves(){
        $bd = loadBBDD();
        $sql = "select * from ";
    }
?>