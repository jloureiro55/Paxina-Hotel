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
    require_once(__DIR__ . '/autoload.php');

    use \functions\functions as func;
    use \conexion\conectDB as db;
    
    session_start();
    if(isset($_POST['busqueda'])){
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $db = new db($_SESSION['rol']);
        $habitaciones = $db->filtrarHabitaciones($checkin,$checkout);
        
    }
    if(!isset($_POST['busqueda'])){
        $db = new db($_SESSION['rol']);
        $habitaciones = $db->CargarHabitaciones();
    }
    ?>
    <body>
        <div class="container-fluid"><!--Contenedor principal-->
            <?php require_once('header.php') ?>
            
            <?php 
            if(isset($habitaciones) && sizeof($habitaciones) > 0){?>
                <div class="container d-flex col-10 p-2">
                <?php 
                for($i = 0 ; $i < sizeof($habitaciones) ; $i++){
                    require 'Carta.php';
                }
                ?>
                </div>
            <?php
            }else{
                ?>
            <div class="container">
            <p>Ninguna Habitación cumple los criterios.</p>
            </div>
            <?php
            }
            ?>
            
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










