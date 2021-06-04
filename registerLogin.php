<?php
    require_once(__DIR__ . '/autoload.php');

    use \functions\functions as func;
    use \conexion\conectDB as db;

    $tool = new func();

    $tool->checkSession();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="externo/fontawesome-free-5.15.2-web/css/all.css"><!--Iconos-->
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css2?family=Charmonman:wght@700&family=Lovers+Quarrel&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Charm&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="externo/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="estilos/style.css">
        <title>Hotel Cache - Login/Register</title>
    </head>

    <?php

    $db = new db($_SESSION['rol']);


    if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST['register'])) { // Comprobamos si se envio el formulario
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $email = $_POST['email'];



        $pass = $tool->encryptionPassword($password);


        if ($tool->phone($phone) == true && !empty($phone)) {

            if ($password == $repassword) {

                if ($tool->validateEmail($email) == true) {

                    // Llamada función
                    $db->registerUser($name, $phone, $pass, $email);
                }
            }
        }
    }

    if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST['login'])) {

        $nameLogin = $_POST['user'];
        $passwordLogin = $_POST['pass'];

        $result = $db->loginUser($nameLogin, $passwordLogin);

        if (is_array($result) && isset($result['password'])) {

            $hash = $result['password'];
            var_dump($result);
            if (password_verify($passwordLogin, $hash)) {
                $db->updateAcceso($result['id']);
                $tool->saveSessionData($result);
            }
        }
    }
    ?>
    <body>
        <div class="container-fluid"><!--Contenedor principal-->
            <?php require_once('header.php') ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 mx-auto p-0">
                        <div class="card-group">
                            <div class="login-box">
                                <div class="login-snip"> 
                                    <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
                                    <label for="tab-1" class="tab">Login</label> 
                                    <input id="tab-2" type="radio" name="tab" class="sign-up">
                                    <label for="tab-2" class="tab">Sign Up</label>


                                    <div class="login-space">
                                        <form class="form-register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
                                            <div class="login">
                                                <div class="group"> <label for="user" class="label">Email</label> <input name="user" id="user" type="text" class="input" placeholder="Enter your email"> </div>
                                                <div class="group"> <label for="pass" class="label">Password</label> <input name="pass" id="pass" type="password" class="input" data-type="password" placeholder="Enter your password"> </div>
                                                <div class="group"> <input id="check" type="checkbox" class="check" checked> <label for="check"><span class="icon"></span> Keep me Signed in</label> </div>
                                                <div class="group"> <input type="submit" class="button" name="login" value="Sign In"> </div>
                                                <div class="hr"></div>
                                                <div class="foot"> <a href="#">Forgot Password?</a> </div>
                                            </div>
                                        </form>


                                        <form class="form-register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
                                            <div class="sign-up-form">
                                                <div class="group"> <label for="user" class="label">Name</label> <input id="user" type="text" class="input" placeholder="Name" name="name"> </div>
                                                <div class="group"> <label for="user" class="label">Phone</label> <input id="user" type="text" class="input" placeholder="Phone" name="phone"> </div>
                                                <div class="group"> <label for="pass" class="label">Password</label> <input id="pass" type="password" class="input" data-type="password" placeholder="Create your password" name="password"> </div>
                                                <div class="group"> <label for="pass" class="label">Repeat Password</label> <input id="pass" type="password" class="input" data-type="password" placeholder="Repeat your password" name="repassword"> </div>
                                                <div class="group"> <label for="pass" class="label">Email Address</label> <input id="pass" type="text" class="input" placeholder="Enter your email address" name="email"> </div>
                                                <div class="group"> <input type="submit" class="button" name="register" value="Sign Up"> </div>
                                                <div class="foot"> <label for="tab-1">Already Member?</label> </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once('footer.php') ?>
        </div>  

        <script src="externo/jquery/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script src="externo/bootstrap/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-highlight@3.5.0/jquery.highlight.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>










