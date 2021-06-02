<?php

namespace conexion;

use \habitacion\Habitacion as habitacion; //Uso de la clase habitacion
use \email\email as email;

/*
 * Clase que contiene la función para realizar una conexion a la base de datos
 * y funciones que hacen uso de esta funcion para conectarse a ella.
 */

class conectDB {

    private $nameBD;
    private $user;
    private $password;
    private $server;
    private $pdo;
    private $fileXML = __DIR__ . '/../../config/configurationBD.xml'; //Propiedad que obtiene la ruta del fichero de configuracion xml
    private $fileXSD = __DIR__ . '/../../config/configurationBD.xsd'; //Propiedad que obtiene la ruta del fichero de configuracion xsd

    /**
     * Función constructor que recibe un rol de un usuario y devuelve una conexion en caso de que
     * la lectura de los ficheros de configuración sea correcta, para ello se utiliza la funcion read_config 
     * que recibe los ficheros xml y xsd.
     * @param type $rol
     */
    function __construct($rol) {

        $data = $this->read_config($this->fileXML, $this->fileXSD, $rol);

        $this->nameBD = $data[0];
        $this->server = $data[1];
        $this->user = $data[2];
        $this->password = $data[3];
        $this->pdo = $this->connect();
    }

    /**
     * Función que devuelve la conexion a la base de datos.
     * Para ello se instacia un objeto PDO que recibirá los parametros que se obtienen en el constructor
     * @return Devuelve \PDO 
     */
    protected function connect() {
        try {
            $pdo = new \PDO("mysql:host=" . $this->server . ";dbname=" . $this->nameBD . ";charset=utf8", $this->user, $this->password);
            return $pdo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que lee la configuración de los ficheros xml y xsd que recibe como parametros, y el rol.
     * Devuelve un array con los datos del fichero xml que se utilizarán para establecer la conexion.
     * @param type $fileXml un fichero xml
     * @param type $fileXsd un fichero xsd
     * @param type $rol String
     * @return un array
     * @throws \PDOException
     */
    function read_config($fileXml, $fileXsd, $rol) {

        $conf = new \DOMDocument(); //Instancia un objeto DOMDocument para poder interpretar los ficheros xml
        $conf->load($fileXml); //Carga el documento xml

        if (!$conf->schemaValidate($fileXsd)) { //Comprueba si el fichero xsd es válido
            throw new \PDOException("Ficheiro de usuarios no valido");
        }


        $xml = simplexml_load_file($fileXml); //Interpreta el fichero xml
        //array que obtiene los datos del fichero xml usando rutas xpath para obtener los datos
        $array = [
            "" . $xml->xpath('//dbname')[0],
            "" . $xml->xpath('//ip')[0],
            "" . $xml->xpath('//nombre[../rol="' . $rol . '"]')[0],
            "" . $xml->xpath('//password[../rol="' . $rol . '"]')[0]
        ];
        return $array;
    }

    /**
     * Funcion que recibe los datos de un usuario que ha de registrarse en la pagina web y
     * que se almacenaran en la base de datos.
     * @param type $name String
     * @param type $phone String
     * @param type $pass String
     * @param type $email String
     * @param type $rol String
     */
    function registerUser($name, $phone, $pass, $email, $rol = 2) {

        try {
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
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que obtiene los datos del usuario
     * 
     * @param string $nameLogin cadea de texto con el nombre del login
     * @return array con los datos
     */
    function loginUser($nameLogin) {
        try {
            $sql = "select usuarios.id as id, nombre, password,email,telf,direccion, rol_usuario, nombre_rol "
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
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que sirve para actualizar el inicio de sesión en la base de datos.
     * Recibe como parametro el id del usuario.
     * @param type $id
     */
    function updateAcceso($id) {
        try {
            $sql = "update usuarios set acceso_log = now() where id = ?;";

            $db = $this->pdo;

            $db->prepare($sql);

            if (($smtp = $db->prepare($sql))) {

                $smtp->bindValue(1, $id, \PDO::PARAM_INT);

                $smtp->execute();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que sirve para actualizar los datos del usuario en la base de datos.
     * Recibe como parametro los nuevos valores.
     * @param type $id
     * @param type $nombre String
     * @param type $email String
     * @param type $telf String
     */
    function updateUserData($id, $nombre, $email, $telf) {
        try {
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
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que guarda el login en la base de datos.
     * @param type $user
     */
    function saveLog($user) {
        try {
            $sql = "insert into Log (user) values (?)";

            $db = $this->pdo;

            if ($stmt = $db->prepare($sql)) {
                $stmt->bindValue(1, $user);
                $stmt->execute();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para filtrar habitaciones cuando un usuario busca una fecha de entrada y de salida y 
     * hace la consulta para ver la disponibilidad de habitaciones dadas las fechas introducidas.
     * Recibe como parametros las fechas y un tipo de habitación, que se inicializa a null para buscar
     * solo las habitaciones que no tienen reserva.  
     * Devuelve un array con las habitaciones disponibles y sus datos.
     * @param type $fecha_entrada 
     * @param type $fecha_salida
     * @param type $tipo_de_habitacion
     * @return array
     */
    function filterRoom($fecha_entrada, $fecha_salida, $tipo_de_habitacion = null) {
        try {
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
                    //Instancia de un objeto habitacion que recibe por parametros los datos de una habitacion, obtenidos de la base de datos.
                    //Una vez creado el objeto con sus valores, se guarda en el array habitaciones. Este array obtendrá
                    //todas las habitaciones disponibles con sus datos correspondientes.
                    $habitacion = new habitacion($row['id'],$row['m2'], $row['ventana'],
                            $row['tipo_de_habitacion'], $row['servicio_limpieza'], $row['internet'],
                            $row['precio'], $row['disponibilidad']);
                    array_push($habitaciones, $habitacion);
                }
            } else {
                echo "ERROR: " . print_r($db->errorInfo());
            }

            unset($stmt);
            return $habitaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que carga en un array las habitaciones están ocupadas, junto con sus datos.  
     * @return array $habitaciones
     */
    function loadRooms($availability) {
        try {
            $habitaciones = array();

            $sql = "select * from habitaciones as h where h.disponibilidad = ?";

            $db = $this->pdo;


            if (($stmt = $db->prepare($sql))) { // Creamos y validamos la sentencia preparada
                $stmt->bindParam(1, $availability);
                $stmt->execute(); // Ejecutamos la setencia preparada

                while ($row = $stmt->fetch(\PDO::FETCH_BOTH)) {
                    $habitacion = new habitacion($row['id'],$row['m2'], $row['ventana'],
                            $row['tipo_de_habitacion'], $row['servicio_limpieza'], $row['internet'],
                            $row['precio'], $row['disponibilidad']);
                    array_push($habitaciones, $habitacion);
                }
            } else {
                echo "ERROR: " . print_r($db->errorInfo());
            }

            unset($stmt);

            return $habitaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que recibe como parametro el tipo de habitación, y si el tipo
     * coincide con el id de la iamgen de la habitación, devuelve
     * los datos de la imagen de la habitación.
     * @param type $tipo 
     * @return type
     */
    function loadRoomImg($tipo) {
        try {
            $datos=[];
            $sql = "select * from imagenes_habitaciones "
                    . "where id_habitacion_tipo like $tipo";
            $db = $this->pdo;

            if ($stmt = $db->prepare($sql)) {
                $stmt->execute();

                while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    array_push($datos,$row);
                }
            }
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función que devuelve los datos del tipo de habitación.
     * Recibe la posición que está dentro de un array de habitaciones, donde
     * se le indicará el tipo.
     * @param type $tipo
     * @return type Array $datos
     */
    function loadRoomData($tipo) {
        try {
            $datos;
            $sql = "select * from habitacion_tipo "
                    . "where id like $tipo";
            $db = $this->pdo;

            if ($stmt = $db->prepare($sql)) {
                $stmt->execute();

                if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $datos = $row;
                }
            }
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    function loadFullData($id) {
        try {
            $sql = "select * from habitaciones where id = $id";

            $db = $this->pdo;

            if ($stmt = $db->prepare($sql)) {
                $stmt->execute();
                if ($row = $stmt->fetch()) {
                    return $row;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    function reserve($id_usuario, $fecha_entrada, $fecha_salida, $id_habitacion, $dias, $servicios = null) {
        try {

            $db = $this->pdo;
            $valid=true;
            $sql = 'insert into reservas (id_usuario,num_dias,fecha_entrada, fecha_salida) values(?,?,?,?)';

            $habitaciones_reservas = 'insert into habitaciones_reservas (num_reserva,id_habitacion) value(?,?)';

            $db->beginTransaction();
            
            if (($smtp = $db->prepare($sql))) {

                $smtp->bindValue(1, $id_usuario, \PDO::PARAM_STR);
                $smtp->bindValue(2, $dias, \PDO::PARAM_STR);
                $smtp->bindValue(3, $fecha_entrada, \PDO::PARAM_STR);
                $smtp->bindValue(4, $fecha_salida, \PDO::PARAM_STR);

                if ($smtp->execute()) {

                    $numReserva = 'select MAX(num_reserva) as ultima_reserva from reservas';

                    if ($sm = $db->prepare($numReserva)) {
                        $sm->execute();

                        if ($row = $sm->fetch(\PDO::FETCH_ASSOC)) {
                            $numeroReserva = $row['ultima_reserva'];
                        }

                        $numHabitacion = "select id from habitaciones where id like $id_habitacion";

                        if ($stp = $db->prepare($numHabitacion)) {
                            $stp->execute();

                            if ($r = $stp->fetch(\PDO::FETCH_ASSOC)) {
                                $id_habit = $r['id'];
                            }
                        }
                    }

                    if ($st = $db->prepare($habitaciones_reservas)) {

                        $st->bindValue(1, $numeroReserva, \PDO::PARAM_STR);
                        $st->bindValue(2, $id_habit, \PDO::PARAM_STR);

                        $st->execute();
                    }

                    if ($servicios != null) {
                        for ($i = 0; $i < sizeof($servicios); $i++) {
                            $addservice = "insert into habitacion_servicio (id_habitacion,id_servicio,fecha_servicio,fecha_fin_servicio) values (?,?,?,?)";
                            if ($s = $db->prepare($addservice)) {
                                $s->bindValue(1, $id_habitacion, \PDO::PARAM_STR);
                                $s->bindValue(2, $servicios[$i], \PDO::PARAM_STR);
                                $s->bindValue(3, $fecha_entrada, \PDO::PARAM_STR);
                                $s->bindValue(4, $fecha_salida, \PDO::PARAM_STR);
                                $s->execute();
                            }
                        }
                    }
                }
            }
            $correo = new email();
            $correo->enviarCorreo("javierloureiro2a@gmail.com","<h1>Reserva pendiente por parte del usuario nº :".$id_usuario."</h1>","Solicitud de reserva");
            $db->commit();
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $db->rollBack();
            $valid=false;
        }
        return $valid;
    }

    function loadServices() {
        try {
            $db = $this->pdo;
            $servicios = [];

            $sql = 'select * from servicios';

            if ($stmt = $db->prepare($sql)) {

                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    array_push($servicios, $row);
                }
            }
            return $servicios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    function ValidateReserve($reservas){
        $sql= "UPDATE reservas
		SET validada=1
        	WHERE num_reserva=?";
        $valid=false;
        $correos = [];
        $db = $this->pdo;
        try{
            $db->beginTransaction();
            foreach($reservas as $reserva){
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $reserva);
                $stmt->execute();
                $user = "SELECT email from usuarios as u inner join reservas as r on u.id=r.id_usuario and r.num_reserva= ?";
                $st = $db->prepare($user);
                $st->bindParam(1, $reserva);
                $st->execute();
                if($row = $st->fetch(\PDO::FETCH_ASSOC)){
                    array_push($correos,$row);
                }
            }
            $db->commit();
            $valid=true;
        } catch (Exception $ex) {
            $valid=false;
            $db->rollBack();
        }
        $correo = new email();
        if($valid){
        foreach($correos as $mail){
            $correo->enviarCorreo($mail['email'],"<h1>Su Reserva se ha realizado con exito!</h1><br><p>Visite su pagina de usuario para mas informacion <a href=\"\">Pulse aquí</a></p>","Reserva con exito");
        }  }
    }
    
    function DeleteReserve($reservas){
        $sql= "DELETE from habitaciones_reservas where num_reserva=?";
        $s= "DELETE from reservas where num_reserva=?";
        $correos = [];
        $db = $this->pdo;
        try{
            $db->beginTransaction();
            foreach($reservas as $reserva){
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $reserva);
                $stmt->execute();
                $st = $db->prepare($s);
                $st->bindParam(1, $reserva);
                $st->execute();
            }
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
        }
    }
    
    function loadPendingReserves(){
        $sql = "SELECT r.num_reserva as reserva, r.fecha_entrada as entrada,r.fecha_salida as salida, hr.id_habitacion as habitacion from reservas as r inner join habitaciones_reservas as hr where r.num_reserva=hr.num_reserva and r.validada=0";
        $reserves=[];
        $db = $this->pdo;
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                array_push($reserves,$row);
            }
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return $reserves;
    }
    
    function toggleRoomState($rooms,$state){
        $sql= "UPDATE habitaciones
		SET disponibilidad = ?
        	WHERE id = ?";
        $db = $this->pdo;
        try{
            $db->beginTransaction();
            foreach($rooms as $room){
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $state, \PDO::PARAM_INT);
                $stmt->bindParam(2, $room);
                $stmt->execute();
            }
            $db->commit();
            
        } catch (Exception $ex) {
            $db->rollBack();
        }
        
    }
    
    function createRoom($habitacion){
        $sql = "insert into habitaciones (m2,ventana,tipo_de_habitacion,servicio_limpieza,internet,precio) values (?,?,?,?,?,?)";
        $db = $this->pdo;
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $habitacion->m2 , \PDO::PARAM_INT);
            $stmt->bindParam(2, $habitacion->ventana , \PDO::PARAM_INT);
            $stmt->bindParam(3, $habitacion->tipo , \PDO::PARAM_INT);
            $stmt->bindParam(4, $habitacion->limpieza , \PDO::PARAM_INT);
            $stmt->bindParam(5, $habitacion->internet , \PDO::PARAM_INT);
            $stmt->bindParam(6, $habitacion->precio , \PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $ex) {

        }
    }
    
    function getTypes(){
        $sql = "select * from habitacion_tipo";
        $types=[];
        $db = $this->pdo;
        
        try{
            $stmt = $db->prepare($sql);
            if($stmt->execute()){
                while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                    array_push($types,$row);
                }
            }
            return $types;
        } catch (Exception $ex) {

        }
    }
}

?>
