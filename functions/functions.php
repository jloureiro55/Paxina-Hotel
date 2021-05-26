<?php

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

function checkSession($result) {

    session_start();
    $_SESSION['usuario'] = $result['nombre'];
    $_SESSION['rol'] = $result['rol_usuario'];
    header("location:index.php");
}
?>

