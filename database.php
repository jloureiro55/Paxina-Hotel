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

function comprobar_usuario($nombre, $clave) {

    /*
     * Comprueba los datos que recibe del formulario del login. Si los datos son correctos
     * devuelve un array con dos campos: codRes (el código del restaurante) y correo 
     * con su correo. En caso de error devuelve false
     */
    $devol = FALSE;
    $bd = loadBBDD();
    $hash = loadPass($nombre);
    if (password_verify($clave, $hash)) {
        $ins = "select codRes, correo from restaurantes where correo = '$nombre' ";
        $resul = $bd->query($ins);
        if ($resul->rowCount() === 1) {
            $devol = $resul->fetch();
        }
    }
    return $devol;
}



?>