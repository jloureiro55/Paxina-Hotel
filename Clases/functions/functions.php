<?php

namespace functions;

class functions {

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

    function encryptionPassword($password) {

        $pass = password_hash($password, PASSWORD_DEFAULT);

        return $pass;
    }

    function validatePass($password, $hash) {

        $encryptionPass = password_verify($password, $hash);

        return $encryptionPass;
    }

    function validateEmail($email) {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    function saveSessionData($result) {

        session_start();
        $_SESSION['UserId'] = $result['id'];
        $_SESSION['rol'] = $result['nombre_rol'];
        $_SESSION['usuario'] = $result['nombre'];

        header("location:index.php");
    }

    function checkSession() {

        if (session_status() == PHP_SESSION_NONE) { // Comprobamos si NO tenemps una sessión activo
            session_start(); // Iniciamos o recuperamos la información de la sessión actual


            if (!isset($_SESSION['rol'])) { // Comprobamos si no existe un ROL asignado
                $_SESSION['rol'] = 'estandar'; // Asignamos el rol por defecto
            }
        }
    }

}
?>

