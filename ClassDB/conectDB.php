<?php

namespace conectDB;

class ConectDB {

    private $nameBD;
    private $user;
    private $password;
    private $server;
    private $pdo;
    private $fileXML = __DIR__ . '/../config/configurationBD.xml';
    private $fileXSD = __DIR__ . '/../config/configurationBD.xsd';

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

    function check_user($nombre, $clave) {
        require_once 'functions/functions.php';

        try {
            $devol = FALSE;
            $bd = loadBBDD();
            $sql = "select  password,rol,telf from usuarios where nombre=?";
            $statement = $bd->prepare($sql);
            if ($statement->execute($nombre)) {
                $row = $statement->fetch();
                if ($row) {
                    $hashed = $row['password'];
                    $rol = $row['rol'];
                    if (validatePass($clave, $hashed)) {
                        $devol = array($nombre, $rol);
                    } else {
                        $devol = false;
                    }
                }
            }
            return $devol;
        } catch (Exception $ex) {
            echo "Error " . $ex->getCode() . " " . $ex->getMessage();
        } finally {
            $statement = null;
            $bd = null;
        }
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

    function loginUser($nameLogin, $passwordLogin) {

        $sql = "select nombre, password,rol_usuario from usuarios where nombre = :nameUser";

        $db = $this->pdo;
        
        $consult = $db->prepare($sql);

        $consult->bindParam(':nameUser', $nameLogin);

        $consult->execute();

        $result = $consult->fetch(\PDO::FETCH_ASSOC);
        
        $hash = $result['password'];
        
        
        if (count($result) > 0 && password_verify($passwordLogin, $hash)) {
            checkSession($result);
            
        } 
        
    }

}

?>
