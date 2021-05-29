<!DOCTYPE html>

<html>
    <?php session_start(); ?>
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
        <style>
            footer{
                bottom: 0;
            }
        </style>
        <title>Pagina de <?php echo $_SESSION['usuario']; ?></title>
        <?php if(!isset($_SESSION['usuario'])){
            header('location:index.php');
        } ?>
    </head>
    <body>
        <?php
           
            require_once 'header.php';?>
        
        <div class="row">
            <div class="col-2 border border-primary p-0 text-center bg-light">
                <img class="col-6 avatar " src="img/Avatar/default-avatar.png">
                <p><?php echo $_SESSION['usuario']; ?></p>
                <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Overview</div>
                <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Reserves</div>
                <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Preferences</div>
                <a href="index.php" id="logout"><div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Log Out</div></a>
            </div>
            <div class="col-10 p-0 border border-primary d-flex d-flex-column">
                <div class="col-12 border border-primary">
                </div>
            </div>
        </div>
        <?php require_once 'footer.php';?>
        
    </body>
    <script>
        document.getElementById('logout').addEventlistener('click',cerrar);
        function cerrar(){
            <?php session_destroy();
                session_unset();
            ?>
        }
    </script>
    <script src="externo/jquery/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script src="externo/bootstrap/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-highlight@3.5.0/jquery.highlight.min.js"></script>
        <script src="js/index.js"></script>
</html>
