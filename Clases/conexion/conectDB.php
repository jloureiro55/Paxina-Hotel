<?php

namespace conexion;

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

    function loadrooms() {

        $bd = loadBBDD();

        $sql = 'select id from habitaciones as r left join habitaciones_reservas as hr'
                . 'on r.id = hr.id_habitacion where num_reserva = null';

        $st = $bd->prepare($sql);

        if ($st->execute()) {

            $row = $st->fetch();

            echo $row;
        }
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
    
    
    
}

?>
