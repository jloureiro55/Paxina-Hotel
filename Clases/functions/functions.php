<?php

namespace functions;

/** 
 * Clase que contiene algunas funciones que se utilizan en distintas
 * partes del proyecto de la pagina web del hotel.
 */
class functions {

    
    /**
     * Función que comprueba un número de telefono
     * @param type $numberPhone un número
     * @return boolean
     */
    function phone($numberPhone) {

        if (is_numeric($numberPhone)) {

            $num = trim($numberPhone);

            if (strlen($num) < 9 || strlen($num) > 9) {

                return false;
            } else {
                return true;
            }
        }
    }

    /** 
     * Función que encripta una contraseña
     * @param type $password String
     * @return Devuelve una contraseña cifrada
     */

    function encryptionPassword($password) {

        $pass = password_hash($password, PASSWORD_DEFAULT);

        return $pass;
    }

    
    /**
     * Función que comprueba una contraseña cifrada con el hash
     * @param type $password
     * @param type $hash
     * @return Devuelve la contraseña cifrada 
     */
    function validatePass($password, $hash) {

        $encryptionPass = password_verify($password, $hash);

        return $encryptionPass;
    }

    
    /** 
     * Función que valida un email introducido es correcto
     * @param type $email
     * @return boolean
     */
    function validateEmail($email) {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /** 
     * Función que guarda el inicio de una sesión y redirige a la pagina principal
     * @param type $result
     */
    function saveSessionData($result) {

        session_start();
        $_SESSION['UserId'] = $result['id'];
        $_SESSION['rol'] = $result['nombre_rol'];
        $_SESSION['usuario'] = $result['nombre'];

        header("location:index.php");
    }

    /** 
     * Función que comprueba el inicio de sesion
     */
    function checkSession() {

        if (session_status() == PHP_SESSION_NONE) { // Comprobamos si NO tenemos una sessión activo
            session_start(); // Iniciamos o recuperamos la información de la sessión actual


            if (!isset($_SESSION['rol'])) { // Comprobamos si no existe un ROL asignado
                $_SESSION['rol'] = 'visitante'; // Asignamos el rol por defecto
            }
        }
    }
    
    function dateDiff($date1, $date2)
{
    $date1_ts = strtotime($date1);
    $date2_ts = strtotime($date2);
    $diff = $date2_ts - $date1_ts;
    return round($diff / 86400);
}

}
?>

