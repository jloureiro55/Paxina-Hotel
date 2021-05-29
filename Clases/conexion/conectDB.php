<?php

namespace conexion;

use \habitacion\Habitacion as habitacion;

class conectDB {

    private $nameBD;
    private $user;
    private $password;
    private $server;
    private $pdo;
    private $fileXML = __DIR__ . '/../../config/configurationBD.xml';
    private $fileXSD = __DIR__ . '/../../config/configurationBD.xsd';

    function __construct($rol) {
        
        $data = $this->leer_config($this->fileXML, $this->fileXSD, $rol);

        $this->nameBD = $data[0];
        $this->server = $data[1];
        $this->user = $data[2];
        $this->password = $data[3];
        $this->pdo = $this->connect();
    }

    protected function connect() {
        try {
            $pdo = new \PDO("mysql:host=" . $this->server . ";dbname=" . $this->nameBD . ";charset=utf8", $this->user, $this->password);
            return $pdo;
        } catch (Exception $ex) {
            
        }
    }

    function leer_config($fileXml, $fileXsd, $rol) {

        $conf = new \DOMDocument();
        $conf->load($fileXml);

        if (!$conf->schemaValidate($fileXsd)) {
            throw new \PDOException("Ficheiro de usuarios no valido");
        }


        $xml = simplexml_load_file($fileXml);

        $array = [
            "" . $xml->xpath('//dbname')[0],
            "" . $xml->xpath('//ip')[0],
            "" . $xml->xpath('//nombre[../rol="' . $rol . '"]')[0],
            "" . $xml->xpath('//password[../rol="' . $rol . '"]')[0]
        ];
        return $array;
    }


    // ROL tiene que ser el de usuario estandar 
    function registerUser($name, $phone, $pass, $email, $rol = 2) {


        $sql = "insert into usuarios (nombre,telf,password,email,rol_usuario) values(?,?,?,?,?)";

        $db = $this->pdo;

        if (($smtp = $db->prepare($sql))) {

            $smtp->bindValue(1, $name, \PDO::PARAM_STR);
            $smtp->bindValue(2, $phone, \PDO::PARAM_INT);
            $smtp->bindValue(3, $pass, \PDO::PARAM_STR);
            $smtp->bindValue(4, $email, \PDO::PARAM_STR);
            $smtp->bindValue(5, $rol, \PDO::PARAM_STR);



            $smtp->execute();
        }
    }

    
    /**
     * Función que obtiene los datos del usuario
     * 
     * @param string $nameLogin cadea de texto con el nombre del login
     * @return array con los datos
     */
    function loginUser($nameLogin) {

        $sql = "select usuarios.id as id, nombre, password, rol_usuario, nombre_rol "
                . "from usuarios "
                . "inner join roles on usuarios.rol_usuario = roles.id"
                . " where nombre = :nameUser";

        $db = $this->pdo;
        
        $consult = $db->prepare($sql);

        $consult->bindParam(':nameUser', $nameLogin);

        $consult->execute();

        $result = $consult->fetch(\PDO::FETCH_ASSOC);
        
        $this->saveLog($result['id']);
        
        return $result;
        
    }
    

    function updateAcceso($id){
        
        $sql = "update usuarios set acceso_log = now() where id = ?;";
        
        $db = $this->pdo;
        
        $db->prepare($sql);
        
         if (($smtp = $db->prepare($sql))) {

            $smtp->bindValue(1, $id, \PDO::PARAM_INT);
     
            $smtp->execute();
        }
        
    }
    
    function updateUserData($id, $nombre, $email, $telf){
        
        $sql = "UPDATE usuarios set nombre= ?, email=?, telf =?, direccion = ?, modificacion_log = now() where id = ?;";
        
        $db = $this->pdo;
        
        $db->prepare($sql);
        
         if (($smtp = $db->prepare($sql))) {

            $smtp->bindValue(1, $nombre, \PDO::PARAM_STR);
            $smtp->bindValue(2, $email, \PDO::PARAM_STR);
            $smtp->bindValue(3, $telf, \PDO::PARAM_STR);
            $smtp->bindValue(1, $direccion, \PDO::PARAM_STR);
     
            $smtp->execute();
        }
        
    }
    
    function saveLog($user){
        $sql = "insert into Log (user) values (?)";
        
        $db = $this->pdo;
        
        if($stmt = $db->prepare($sql)){
            $stmt->bindValue(1, $user);
            $stmt->execute();
        }
        
    }
    
    
    function filtrarHabitaciones($fecha_entrada, $fecha_salida, $tipo_de_habitacion = null) {

        $habitaciones = array();

        $sql = "select * 
                 from habitaciones as h
                    where h.id not in (
                       select hr.id_habitacion
                         from reservas as v inner join habitaciones_reservas as hr
                         on v.num_reserva = hr.num_reserva
                            and ? >= v.fecha_entrada
                              and ? <= v.fecha_salida
                                and ? >= v.fecha_entrada
                        ) 
                          and h.disponibilidad = 1";

        if ($tipo_de_habitacion != null) {
            $sql .= "and habitaciones.tipo_de_habitacion = ?;";
        }

        $db = $this->pdo;


        if (($stmt = $db->prepare($sql))) { // Creamos y validamos la sentencia preparada
            $stmt->bindValue(1, $fecha_entrada, \PDO::PARAM_STR);
            $stmt->bindValue(2, $fecha_entrada, \PDO::PARAM_STR);
            $stmt->bindValue(3, $fecha_salida, \PDO::PARAM_STR);

            if ($tipo_de_habitacion != null) {
                $stmt->bindValue(4, $tipo_de_habitacion, \PDO::PARAM_STR);
            }
            $stmt->execute(); // Ejecutamos la setencia preparada

            while ($row = $stmt->fetch(\PDO::FETCH_BOTH)) {

                $habitacion = new habitacion($row['id'], $row['m2'], $row['ventana'],
                        $row['tipo_de_habitacion'], $row['servicio_limpieza'], $row['internet'],
                        $row['precio'], $row['disponibilidad']);
                array_push($habitaciones, $habitacion);
            }
        } else {
            echo "ERROR: " . print_r($db->errorInfo());
        }

        unset($stmt);

        return $habitaciones;
    }
    
    function CargarHabitaciones() {

        $habitaciones = array();

        $sql = "select * 
                 from habitaciones as h
                          where h.disponibilidad = 1";

        $db = $this->pdo;


        if (($stmt = $db->prepare($sql))) { // Creamos y validamos la sentencia preparada

            $stmt->execute(); // Ejecutamos la setencia preparada

            while ($row = $stmt->fetch()) {

                $habitacion = new habitacion($row['id'], $row['m2'], $row['ventana'],
                        $row['tipo_de_habitacion'], $row['servicio_limpieza'], $row['internet'],
                        $row['precio'], $row['disponibilidad']);
                array_push($habitaciones, $habitacion);
            }
        } else {
            echo "ERROR: " . print_r($db->errorInfo());
        }

        unset($stmt);

        return $habitaciones;
    } 
    
    function loadRoomData($tipo){
        $datos = " ";
        $sql = "select * from imagenes_habitaciones"
                . "where id_habitacion_tipo like $tipo";
        
        $db= $this->pdo;
        
        if($stmt= $db->prepare($sql)){
            $stmt->execute();
            
            if($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $datos = $row;
                var_dump($datos);
            }
            
        }
        return $datos;
    }
}

?>
